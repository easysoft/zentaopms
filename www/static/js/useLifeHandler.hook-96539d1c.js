import{E as e}from"./chartEditStore-53dc1709.js";import{aT as u}from"./index-5427279b.js";const m={},c={echarts:u},f=t=>{const n=t.events||{};return{[e.BEFORE_MOUNT](o){m[t.id]=o.component;const s=(n[e.BEFORE_MOUNT]||"").trim();i(s,o)},[e.MOUNTED](o){const s=(n[e.MOUNTED]||"").trim();i(s,o)}}};function i(t,n){try{Function(`
      "use strict";
      return (
        async function(e, components, node_modules){
          const {${Object.keys(c).join()}} = node_modules;
          ${t}
        }
      )`)().bind(n==null?void 0:n.component)(n,m,c)}catch(r){console.error(r)}}export{c as n,f as u};
