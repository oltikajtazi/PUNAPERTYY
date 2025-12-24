/* Chat widget behaviour (front-end-only, simulated bot)
   - Stores messages in localStorage
   - Simulates typing and contextual replies
   - Supports image attachments and quick replies
   - Draggable header, minimize/close, persistent position
*/
(function(){
    const LS_KEY = 'puna_chat_messages_v1';
    const launcher = document.getElementById('chat-launcher');
    const widget = document.getElementById('chat-widget');
    const openBtn = document.getElementById('chat-open');
    const closeBtn = document.getElementById('chat-close');
    const minimizeBtn = document.getElementById('chat-minimize');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-text');
    const msgs = document.getElementById('chat-messages');
    const quick = document.getElementById('chat-quick-replies');
    const fileInput = document.getElementById('chat-file');

    let messages = [];
    let isOpen = false;

    function save(){ localStorage.setItem(LS_KEY, JSON.stringify(messages)); }
    function load(){
        try{ messages = JSON.parse(localStorage.getItem(LS_KEY)) || []; }catch(e){ messages = []; }
    }

    // --- Site index for simple QA ---
    const SITE_PAGES = ['index.html','about.html','projects.html','contact.html'];
    let pageIndex = []; // { source, url, text, tokens }

    function tokenize(s){ return (s||'').toLowerCase().replace(/[^a-z0-9çëäöüõßÿğşžŭėàáâäîíìëéêîôûùüỳỳśčńřťž]+/gi,' ').split(/\s+/).filter(Boolean); }

    async function buildIndex(){
        pageIndex = [];
        // include current DOM text first
        try{
            const docText = collectTextFromDocument(document);
            pageIndex.push({ source: 'Kreu', url: 'index.html', text: docText, tokens: tokenize(docText) });
        }catch(e){}

        // try to fetch other pages (may fail on file:// without server)
        for(const p of SITE_PAGES){
            if(p === 'index.html') continue;
            try{
                const res = await fetch(p);
                if(!res.ok) continue;
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const t = collectTextFromDocument(doc);
                pageIndex.push({ source: p.replace('.html',''), url: p, text: t, tokens: tokenize(t) });
            }catch(e){ /* ignore fetch errors */ }
        }
    }

    function collectTextFromDocument(doc){
        const parts = [];
        const els = doc.querySelectorAll('h1,h2,h3,h4,p,li,span');
        els.forEach(e=>{ const txt = e.textContent && e.textContent.trim(); if(txt && txt.length>0) parts.push(txt); });
        return parts.join('\n');
    }

    function searchIndex(query){
        const qtokens = tokenize(query);
        if(qtokens.length===0) return null;
        let best = null; let bestScore = 0;
        for(const page of pageIndex){
            let score = 0;
            for(const t of qtokens) score += (page.tokens.filter(x=> x.includes(t)).length);
            if(score > bestScore){ bestScore = score; best = page; }
        }
        if(!best || bestScore===0) return null;
        // try to extract a sentence with highest overlap
        const sentences = best.text.split(/[\.\!\?\n]+/).map(s=>s.trim()).filter(Boolean);
        let bestSent = sentences[0] || best.text;
        let sentScore = 0;
        for(const s of sentences){
            let sc = 0; const st = tokenize(s);
            for(const t of qtokens) sc += st.filter(x=> x.includes(t)).length;
            if(sc > sentScore){ sentScore = sc; bestSent = s; }
        }
        return { page: best, snippet: bestSent, score: bestScore };
    }

    function scrollToBottom(){ msgs.scrollTop = msgs.scrollHeight; }

    function isNearBottom(){
        return msgs.scrollHeight - msgs.clientHeight - msgs.scrollTop < 140;
    }

    function render(){
        const wasNearBottom = isNearBottom();
        msgs.innerHTML = '';
        messages.forEach(m => {
            const el = document.createElement('div');
            el.className = 'msg '+(m.from==='user'?'user':'bot');
            if(m.type === 'text') el.textContent = m.text;
            else if(m.type==='image'){
                const img = document.createElement('img'); img.src = m.src; el.appendChild(img);
            }
            msgs.appendChild(el);
        });
        if(wasNearBottom) scrollToBottom();
    }

    function appendTyping(){
        const el = document.createElement('div'); el.className='msg bot typing';
        el.innerHTML = '<span class="dots"><span></span><span></span><span></span></span>';
        msgs.appendChild(el); scrollToBottom(); return el;
    }

    function pushMessage(from, payload){
        payload.from = from;
        messages.push(payload); save(); render();
    }

    async function simulateBotReply(userText){
        const typingEl = appendTyping();
        const delay = 600 + Math.min(1200, userText.length*15);
        // ensure index exists
        if(pageIndex.length === 0) await buildIndex();
        setTimeout(()=>{}, 0);
        setTimeout(async ()=>{
            typingEl.remove();
            // first try site-aware search
            const found = searchIndex(userText);
            if(found){
                const reply = `${found.snippet} — Më shumë: ${found.page.url}`;
                pushMessage('bot', { type:'text', text: reply });
            }else{
                // fallback to conventional replies
                const reply = decideReply(userText);
                pushMessage('bot', { type:'text', text: reply });
            }
        }, delay);
    }

    function decideReply(text){
        if(!text) return 'Mund ta shpjegoni pak më shumë?';
        const t = text.toLowerCase();
        if(/price|çmim|sa|kost/i.test(t)) return 'Shërbimet fillojnë nga 80€ — shkruaj "Çmimet" për më shumë detaje.';
        if(/hello|hi|pershendetje|hej/i.test(t)) return 'Përshëndetje! Si mund t\u00eb ju ndihmoj sot?';
        if(/portfolio|projek/i.test(t)) return 'Mund të shikoni portofolin te seksioni "Projektet" në sit.';
        if(/kontakt|kontaktimi|kontaktoj/i.test(t)) return 'Mund të më shkruani këtu ose të përdorni formularin në Kontakt.';
        return 'Faleminderit për mesazhin! Do ta shqyrtoj dhe do t\u00eb përgjigjem së shpejti.';
    }

    function sendText(text){
        if(!text.trim()) return;
        pushMessage('user', { type:'text', text: text.trim() });
        input.value='';
        simulateBotReply(text);
    }

    // Quick replies
    const quickItems = ['Çmimet','Projektet','Kontakt'];
    function renderQuick(){
        quick.innerHTML = '';
        quickItems.forEach(q=>{
            const b = document.createElement('button'); b.type='button'; b.textContent = q;
            b.addEventListener('click', ()=>{ sendText(q); }); quick.appendChild(b);
        });
    }

    // Attach images
    document.getElementById('chat-attach').addEventListener('click', ()=> fileInput.click());
    fileInput.addEventListener('change', (e)=>{
        const f = e.target.files && e.target.files[0]; if(!f) return; const reader = new FileReader();
        reader.onload = function(){ pushMessage('user', { type:'image', src: reader.result }); simulateBotReply('image'); };
        reader.readAsDataURL(f); fileInput.value = '';
    });

    // Form submit
    form.addEventListener('submit', (ev)=>{ ev.preventDefault(); sendText(input.value); });

    // Open/close/minimize
    openBtn.addEventListener('click', ()=>{ isOpen = true; widget.classList.add('open'); launcher.style.display='none'; widget.setAttribute('aria-hidden','false'); render(); input.focus(); });
    closeBtn.addEventListener('click', ()=>{ isOpen = false; widget.classList.remove('open'); launcher.style.display='block'; widget.setAttribute('aria-hidden','true'); });
    minimizeBtn.addEventListener('click', ()=>{ isOpen = false; widget.classList.remove('open'); launcher.style.display='block'; widget.setAttribute('aria-hidden','true'); });

    // Mobile nav toggle (simple)
    const navToggle = document.getElementById('nav-toggle');
    const mainNav = document.getElementById('main-nav');
    if(navToggle && mainNav){
        navToggle.addEventListener('click', ()=>{ mainNav.classList.toggle('open'); });
        Array.from(mainNav.querySelectorAll('a')).forEach(a=> a.addEventListener('click', ()=> mainNav.classList.remove('open')));
    }

    // Draggable header (disabled on small screens)
    (function makeDraggable(){
        const header = document.getElementById('chat-header');
        let dragging=false, startX=0, startY=0, startLeft=0, startTop=0;
        function isSmall(){ return window.matchMedia('(max-width:420px)').matches; }
        header.addEventListener('pointerdown', (e)=>{
            if(isSmall()) return; dragging=true; header.setPointerCapture(e.pointerId); startX=e.clientX; startY=e.clientY;
            const rect = widget.getBoundingClientRect(); startLeft = rect.left; startTop = rect.top; widget.style.transition='none';
        });
        window.addEventListener('pointermove', (e)=>{ if(!dragging) return; const dx = e.clientX - startX, dy = e.clientY - startY; widget.style.left = (startLeft + dx) + 'px'; widget.style.top = (startTop + dy) + 'px'; widget.style.right = 'auto'; widget.style.bottom = 'auto'; widget.style.position='fixed'; });
        window.addEventListener('pointerup', (e)=>{ if(!dragging) return; dragging=false; try{ header.releasePointerCapture(e.pointerId);}catch(e){} widget.style.cursor='grab'; });
    })();

    // Boot
    load(); renderQuick(); render();
    if(messages.length===0){ pushMessage('bot',{ type:'text', text:'Përshëndetje! Unë jam Olti Assistant. Si mund t\u000a ndihmoj?' }); }

})();
