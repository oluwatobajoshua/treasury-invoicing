<?php
/**
 * Reusable Microsoft Graph User Picker Element
 * Usage (in any template):
 *   echo $this->element('graph_user_picker');
 *   Then, to open the picker from a button click:
 *     <button onclick="openGraphUserPicker({ multiple: true, onConfirm: (emails, users) => { ... } })">Open</button>
 *
 * The picker lazily fetches users from /auth/get-graph-users when opened, and
 * provides client-side filtering. If the preload fails/empty, it allows remote
 * search via Admin/Users/searchGraph for queries with length >= 2.
 */
?>
<script>
(function(){
  if (window.GraphUserPickerLoaded) return; // idempotent
  window.GraphUserPickerLoaded = true;

  const SEARCH_ENDPOINT = '<?= $this->Url->build(["prefix"=>"Admin","controller"=>"Users","action"=>"searchGraph"]) ?>';
  const GET_USERS_ENDPOINT = '<?= $this->Url->build(["prefix"=>false,"controller"=>"Auth","action"=>"getGraphUsers"]) ?>';

  function normalize(u){
    const email = (u.email || u.mail || u.userPrincipalName || u.upn || '').trim();
    const name = (u.name || u.displayName || '').trim();
    const firstName = (u.firstName || u.givenName || '').trim();
    const lastName = (u.lastName || u.surname || '').trim();
    const department = (u.department || '').trim();
    const jobTitle = (u.jobTitle || '').trim();
    return { email, name, firstName, lastName, department, jobTitle };
  }

  function renderList(items, selectedMap, multiple){
    if (!Array.isArray(items) || !items.length){
      return '<div style="padding:1rem;color:#666;">No users to show.</div>';
    }
    return items.map(raw=>{
      const u = normalize(raw);
      const initial = (u.name || u.email || '?').substring(0,1).toUpperCase();
      const checked = selectedMap.has(u.email) ? 'checked' : '';
      const selector = multiple
        ? `<input type="checkbox" class="pick-cb" data-email="${u.email}" ${checked} style="width:18px;height:18px;">`
        : `<input type="radio" name="pick-radio" class="pick-cb" data-email="${u.email}" ${checked} style="width:18px;height:18px;">`;
      return `<label class="pick-row" data-email="${u.email}" data-first="${u.firstName}" data-last="${u.lastName}" data-dept="${u.department}" style="display:flex;gap:.75rem;align-items:center;padding:.6rem .5rem;border-bottom:1px solid #f3f3f3;cursor:pointer;">
                ${selector}
                <div style="width:28px;height:28px;border-radius:50%;background:#0c5343;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">${initial}</div>
                <div style="flex:1;">
                  <div style="font-weight:600;">${u.name || u.email}</div>
                  <div class="muted" style="font-size:.9rem;color:#555;">${u.email}${u.department?(' · '+u.department):''}${u.jobTitle?(' · '+u.jobTitle):''}</div>
                </div>
              </label>`;
    }).join('');
  }

  function openGraphUserPicker(opts){
    const options = Object.assign({ multiple: true, minSearch: 2, title: 'Select users from Microsoft 365', onConfirm: ()=>{} }, opts||{});
    const modal = document.createElement('div');
    modal.id = 'graph-user-picker-modal';
    modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;z-index:1050;';
    modal.innerHTML = `
      <div style="background:#fff;border-radius:12px;max-width:840px;width:94%;max-height:82vh;display:flex;flex-direction:column;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
          <h3 style="margin:0;font-size:1.1rem;"><i class="fas fa-users"></i> ${options.title}</h3>
          <button type="button" id="gup-close" class="btn btn-outline" style="padding:.375rem .75rem"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:0.9rem 1.25rem;border-bottom:1px solid #f2f2f2;">
          <input type="text" id="gup-search" placeholder="Search by name or email..." style="width:100%;padding:.65rem;border:1px solid #ddd;border-radius:8px;">
          <div id="gup-help" class="muted" style="margin-top:.5rem;color:#666;font-size:.9rem;">Loading directory…</div>
        </div>
        <div id="gup-list" style="flex:1;overflow:auto;padding:0.75rem 1.25rem;">
          <div style="padding:1rem;color:#666;">Loading…</div>
        </div>
        <div style="padding:0.9rem 1.25rem;border-top:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
          <div id="gup-count" class="muted" style="font-size:.9rem;color:#666;">No users selected</div>
          <div style="display:flex;gap:.5rem;">
            <button type="button" id="gup-cancel" class="btn" style="background:#6b7280;color:#fff;">Cancel</button>
            ${options.multiple ? '<button type="button" id="gup-confirm" class="btn btn-primary">Add Selected</button>' : ''}
          </div>
        </div>
      </div>`;
    document.body.appendChild(modal);

    const listEl = modal.querySelector('#gup-list');
    const helpEl = modal.querySelector('#gup-help');
    const countEl = modal.querySelector('#gup-count');
    const searchEl = modal.querySelector('#gup-search');
    const closeBtn = modal.querySelector('#gup-close');
    const cancelBtn = modal.querySelector('#gup-cancel');
    const confirmBtn = modal.querySelector('#gup-confirm');

    const selectedMap = new Map();
    let fullList = [];

    function updateSelectedCount(){
      const n = selectedMap.size;
      if (countEl) countEl.textContent = n ? `${n} user${n>1?'s':''} selected` : 'No users selected';
    }

    function wireRowHandlers(single){
      listEl.querySelectorAll('.pick-cb').forEach(cb=>{
        cb.addEventListener('change', ()=>{
          const em = cb.getAttribute('data-email');
          if (single){
            selectedMap.clear();
            if (cb.checked) selectedMap.set(em, true);
            // uncheck others
            listEl.querySelectorAll('.pick-cb').forEach(other=>{ if (other!==cb) other.checked=false; });
            if (!options.multiple) doConfirmAndClose();
          } else {
            if (cb.checked) selectedMap.set(em, true); else selectedMap.delete(em);
          }
          updateSelectedCount();
        });
      });
    }

    function filterAndRender(){
      const q = searchEl.value.trim().toLowerCase();
      let items = fullList;
      if (q && q.length >= options.minSearch){
        items = fullList.filter(u => {
          const name = (u.name || u.displayName || '').toLowerCase();
          const email = (u.email || u.mail || u.userPrincipalName || u.upn || '').toLowerCase();
          return (name && name.includes(q)) || (email && email.includes(q));
        });
        helpEl.textContent = `${items.length} match${items.length!==1?'es':''}.`;
      } else {
        helpEl.textContent = fullList.length ? 'Type to filter the list.' : 'No users loaded. Try logging out/in to refresh your session.';
      }
      listEl.innerHTML = renderList(items, selectedMap, options.multiple);
      wireRowHandlers(!options.multiple);
      updateSelectedCount();
    }

    function doConfirmAndClose(){
      const emails = Array.from(selectedMap.keys());
      const users = fullList.filter(u => emails.includes(normalize(u).email)).map(normalize);
      try { options.onConfirm(emails, users); } catch(e) { /* ignore */ }
      modal.remove();
    }

    // Close handlers
    closeBtn.addEventListener('click', ()=> modal.remove());
    cancelBtn.addEventListener('click', ()=> modal.remove());
    modal.addEventListener('click', (e)=>{ if (e.target === modal) modal.remove(); });
    if (confirmBtn) confirmBtn.addEventListener('click', doConfirmAndClose);

    // Lazy fetch on open
    helpEl.textContent = 'Loading directory…';
    fetch(GET_USERS_ENDPOINT, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
      .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
      .then(data => {
        if (data && data.users && Array.isArray(data.users)){
          fullList = data.users;
          filterAndRender();
          helpEl.textContent = fullList.length ? `Loaded ${fullList.length} users. Type to filter.` : 'No users loaded. Use search or re-login.';
        } else if (data && data.error){
          helpEl.textContent = 'Failed to load users: ' + data.error;
          fullList = [];
          filterAndRender();
        } else {
          fullList = [];
          filterAndRender();
        }
      })
      .catch(() => {
        // If preload fails, keep list empty and allow remote search
        fullList = [];
        filterAndRender();
        helpEl.textContent = 'Directory load failed. Type to search your org.';
      });

    // Remote search fallback when no preloaded users
    let timer=null, last='';
    searchEl.addEventListener('input', ()=>{
      const q = searchEl.value.trim();
      if (q === last) return; last = q;
      if (timer) clearTimeout(timer);
      if (fullList.length){
        // We have preloaded list: just filter locally
        timer = setTimeout(filterAndRender, 100);
        return;
      }
      if (q.length < options.minSearch){
        listEl.innerHTML = '<div style="padding:1rem;color:#666;">Type at least '+options.minSearch+' characters to search.</div>';
        return;
      }
      helpEl.textContent = 'Searching…';
      timer = setTimeout(async ()=>{
        try {
          const url = `${SEARCH_ENDPOINT}?q=${encodeURIComponent(q)}`;
          const res = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
          const data = await res.json();
          const items = Array.isArray(data.results) ? data.results : [];
          listEl.innerHTML = renderList(items, selectedMap, options.multiple);
          wireRowHandlers(!options.multiple);
          helpEl.textContent = items.length ? `${items.length} match${items.length!==1?'es':''}.` : 'No matches.';
          // When using fallback, we treat items as the working set for selection.
          fullList = items;
          updateSelectedCount();
        } catch(e){
          listEl.innerHTML = '<div style="padding:1rem;color:#c62828;">Search failed. Please try again.</div>';
          helpEl.textContent = 'There was an error querying Microsoft Graph.';
        }
      }, 280);
    });
  }

  // Expose globally
  window.openGraphUserPicker = openGraphUserPicker;
})();
</script>
