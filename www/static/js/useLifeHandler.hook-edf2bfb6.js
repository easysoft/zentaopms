var f=Object.defineProperty;var u=Object.getOwnPropertySymbols;var O=Object.prototype.hasOwnProperty,d=Object.prototype.propertyIsEnumerable;var E=(e,n,t)=>n in e?f(e,n,{enumerable:!0,configurable:!0,writable:!0,value:t}):e[n]=t,c=(e,n)=>{for(var t in n||(n={}))O.call(n,t)&&E(e,t,n[t]);if(u)for(var t of u(n))d.call(n,t)&&E(e,t,n[t]);return e};import{E as r}from"./chartEditStore-8254eca3.js";import{ck as p}from"./index-67a30bc6.js";const m={},i={echarts:p},D=e=>{if(!e.events)return{};const n={};for(const o in e.events.baseEvent){const s=e.events.baseEvent[o];s&&(n[o]=N(s))}const t=e.events.advancedEvents||{},v={[r.VNODE_BEFORE_MOUNT](o){m[e.id]=o.component;const s=(t[r.VNODE_BEFORE_MOUNT]||"").trim();a(s,o)},[r.VNODE_MOUNTED](o){const s=(t[r.VNODE_MOUNTED]||"").trim();a(s,o)}};return c(c({},n),v)};function N(e){try{return new Function(`
      return (
        async function(mouseEvent){
          ${e}
        }
      )`)()}catch(n){console.error(n)}}function a(e,n){try{Function(`
      "use strict";
      return (
        async function(e, components, node_modules){
          const {${Object.keys(i).join()}} = node_modules;
          ${e}
        }
      )`)().bind(n==null?void 0:n.component)(n,m,i)}catch(t){console.error(t)}}export{i as n,D as u};
