var ue=Object.defineProperty;var G=Object.getOwnPropertySymbols;var oe=Object.prototype.hasOwnProperty,ne=Object.prototype.propertyIsEnumerable;var H=(l,n,s)=>n in l?ue(l,n,{enumerable:!0,configurable:!0,writable:!0,value:s}):l[n]=s,U=(l,n)=>{for(var s in n||(n={}))oe.call(n,s)&&H(l,s,n[s]);if(G)for(var s of G(n))ne.call(n,s)&&H(l,s,n[s]);return l};var J=(l,n,s)=>new Promise((j,f)=>{var C=i=>{try{A(s.next(i))}catch(g){f(g)}},x=i=>{try{A(s.throw(i))}catch(g){f(g)}},A=i=>i.done?j(i.value):Promise.resolve(i.value).then(C,x);A((s=s.apply(l,n)).next())});import{b as ae,d as K,j as M,L as se,r as a,o as c,c as F,f as t,w as e,an as le,u as r,F as h,q as y,p as d,t as m,g as o,E as O,v as ce,x as de}from"./index-5427279b.js";import{M as ie}from"./EditorWorker-a9d0b3e4.js";import"./editorWorker-a0599278.js";import{u as Q}from"./useTargetData.hook-f855ff49.js";import{i as re}from"./icon-d6196121.js";import{E as D}from"./chartEditStore-53dc1709.js";import{n as pe}from"./useLifeHandler.hook-96539d1c.js";import"./plugin-49832ae5.js";const _e=`
console.log(e)
`,Ee=`
console.log(echarts)
`,Fe=`
console.log(components)
`,me=`
console.log(node_modules)
`,fe=`
// \u5728\u6E32\u67D3\u4E4B\u540E\u624D\u80FD\u83B7\u53D6 dom \u5B9E\u4F8B
e.el.addEventListener('click', () => {
  alert('\u6211\u89E6\u53D1\u62C9~');
}, false)
`,he=`
await import('https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/lodash.js/4.17.21/lodash.js')

// lodash \u9ED8\u8BA4\u8D4B\u503C\u7ED9 "_"
console.log('isEqual', _.isEqual(['1'], ['1']))
`,ge=`
// \u83B7\u53D6echart\u5B9E\u4F8B
const chart = this.refs.vChartRef.chart

// \u56FE\u8868\u8BBE\u7F6Etooltip
chart.setOption({
  tooltip: {
    trigger: 'axis', //item
    enterable: true, 
    formatter (params) {
      return\`
        <div>
          <img src="https://portrait.gitee.com/uploads/avatars/user/1654/4964818_MTrun_1653229420.png!avatar30">
          <b><a href="https://gitee.com/dromara/go-view">\u300A\u8FD9\u662F\u4E00\u4E2A\u81EA\u5B9A\u4E49\u7684tooltip\u300B</a></b>
        <div>
        <div style='border-radius:35px;color:#666'>
        \${Object.entries(params[0].value).map(kv => \`<div>\${kv[0]}:\${kv[1]}</div>\`).join('')}
        </div>
      \`;
    },
  }
})
`,Be=`
// \u7EC4\u4EF6\u6837\u5F0F\u4F5C\u7528\u57DF\u6807\u8BC6
const scoped = this.subTree.scopeId
function loadStyleString(css){
	let style = document.createElement('style')
	style.type = 'text/css'
	style.appendChild(document.createTextNode(css))
	let head = document.getElementsByTagName('head')[0]
	head.appendChild(style)
}
loadStyleString(\`
.dv-scroll-board[\${scoped}] {
  position: relative;
  overflow: hidden;
}
.dv-scroll-board[\${scoped}]::before {
  content: '';
  display: block;
  position: absolute;
  top: -20%;
  left: -100%;
  width: 550px;
  height: 60px;
  transform: rotate(-45deg);
  background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.3), rgba(0, 0, 0, 0));
  animation: cross 2s infinite;
}
@keyframes cross{
  to{
    top: 80%;
    left: 100%;
    transform: rotate(-45deg);
  }
}
\`)
`,De=`
const chart = this.refs.vChartRef.chart
// \u5B9A\u4E49\u5730\u56FE\u539F\u70B9\u5927\u5C0F \u540C\u7406\u53EF\u81EA\u5B9A\u4E49\u6807\u7B7E\u7B49\u7B49\u5185\u5BB9
this.props.chartConfig.option.series[0].symbolSize = (val) => {
  return Math.sqrt(val[2]) / 3;
}
this.setupState.vEchartsSetOption();
let i = 0; // \u5F53\u524D\u8F6E\u64AD\u7D22\u5F15
const len = 3; // \u8F6E\u64AD\u90E8\u5206\u63D0\u793A
(function showTips() {
  const action = (type, dataIndex) => {
    chart.dispatchAction({
      type,
      dataIndex,
      seriesIndex: 0,
    });
  }
  setInterval(() => {
    action("downplay", i);
    action("hideTip", i);
    if (i === len) i = 0;
    i++;
    action("highlight", i);
    action("showTip", i);
  }, 2000);
})()
`,ve=[{description:"\u83B7\u53D6\u5F53\u524D\u7EC4\u4EF6\u5B9E\u4F8B",code:_e},{description:"\u83B7\u53D6\u5168\u5C40 echarts \u5B9E\u4F8B",code:Ee},{description:"\u83B7\u53D6\u7EC4\u4EF6\u56FE\u8868\u96C6\u5408",code:Fe},{description:"\u83B7\u53D6 nodeModules \u5B9E\u4F8B",code:me},{description:"\u83B7\u53D6\u8FDC\u7A0B CDN \u5E93",code:he},{description:"\u8BBE\u7F6E\u6587\u5B57\u7EC4\u4EF6\u70B9\u51FB\u4E8B\u4EF6",code:fe},{description:"\u4FEE\u6539\u56FE\u8868 tooltip",code:ge},{description:"\u6DFB\u52A0\u3010\u8F6E\u64AD\u5217\u8868\u3011\u6837\u5F0F",code:Be},{description:"\u4FEE\u6539\u3010\u5730\u56FE\u3011\u5706\u70B9\uFF0C\u65B0\u589E\u63D0\u793A\u81EA\u52A8\u8F6E\u64AD",code:De}];const $=l=>(ce("data-v-0c72195a"),l=l(),de(),l),ye=o(" \u7F16\u8F91 "),Ce={class:"func-keyword"},xe=o(" (e, components, echarts, node_modules) { "),Ae={class:"go-ml-4"},be=$(()=>d("p",null,[o("}"),d("span",null,",")],-1)),we=o("\u9AD8\u7EA7\u4E8B\u4EF6\u7F16\u8F91\u5668\uFF08\u914D\u5408\u6E90\u7801\u4F7F\u7528\uFF09"),ke={class:"go-pl-3"},Te=$(()=>d("span",{class:"func-keyword"},"async function \xA0\xA0",-1)),Se={class:"func-keyNameWord"},Me=$(()=>d("p",{class:"go-pl-3 func-keyNameWord"},"}",-1)),Oe=o("\u89E6\u53D1\u5BF9\u5E94\u751F\u547D\u5468\u671F\u4E8B\u4EF6\u65F6\u63A5\u6536\u7684\u53C2\u6570"),$e=o("\u56FE\u8868\u7EC4\u4EF6\u5B9E\u4F8B"),je=$(()=>d("br",null,null,-1)),Ie=o("\u5F53\u524D\u5927\u5C4F\u5185\u6240\u6709\u7EC4\u4EF6\u7684\u96C6\u5408id \u56FE\u8868\u7EC4\u4EF6\u4E2D\u7684\u914D\u7F6Eid\uFF0C\u53EF\u4EE5\u83B7\u53D6\u5176\u4ED6\u56FE\u8868\u7EC4\u4EF6\u8FDB\u884C\u63A7\u5236"),Ue=o("\u4EE5\u4E0B\u662F\u5185\u7F6E\u5728\u4EE3\u7801\u73AF\u5883\u4E2D\u53EF\u7528\u7684\u5305\u53D8\u91CF"),Le=$(()=>d("br",null,null,-1)),Ne={class:"go-flex-items-center"},Ve=o(" \u63D0\u793A "),ze=o("\u901A\u8FC7\u63D0\u4F9B\u7684\u53C2\u6570\u53EF\u4E3A\u56FE\u8868\u589E\u52A0\u5B9A\u5236\u5316\u7684tooltip\u3001\u4EA4\u4E92\u4E8B\u4EF6\u7B49\u7B49"),qe=o("\u53D6\u6D88"),Pe=o("\u4FDD\u5B58"),Re=K({__name:"index",setup(l){const{targetData:n,chartEditStore:s}=Q(),{DocumentTextIcon:j,ChevronDownIcon:f,PencilIcon:C}=re.ionicons5,x={[D.BEFORE_MOUNT]:"\u6E32\u67D3\u4E4B\u524D",[D.MOUNTED]:"\u6E32\u67D3\u4E4B\u540E"},A={[D.BEFORE_MOUNT]:"\u6B64\u65F6\u7EC4\u4EF6 DOM \u8FD8\u672A\u5B58\u5728",[D.MOUNTED]:"\u6B64\u65F6\u7EC4\u4EF6 DOM \u5DF2\u7ECF\u5B58\u5728"},i=M(!1),g=M(D.MOUNTED);let v=M(U({},n.value.events));const X=M(!1),q=()=>{let b="",_="",w="";return X.value=Object.entries(v.value).every(([k,T])=>{try{const B=Object.getPrototypeOf(function(){return J(this,null,function*(){})}).constructor;return new B(T),!0}catch(B){return _=B.message,w=B.name,b=k,!1}}),{errorFn:b,message:_,name:w}},P=()=>{i.value=!1},Y=()=>{if(q().errorFn){window.$message.error("\u4E8B\u4EF6\u51FD\u6570\u9519\u8BEF\uFF0C\u65E0\u6CD5\u8FDB\u884C\u4FDD\u5B58");return}Object.values(v.value).join("").trim()===""?n.value.events=void 0:n.value.events=U({},v.value),P()};return se(()=>i.value,b=>{b&&(v.value=U({},n.value.events))}),(b,_)=>{const w=a("n-icon"),k=a("n-button"),T=a("n-code"),B=a("n-card"),E=a("n-collapse-item"),p=a("n-text"),L=a("n-space"),I=a("n-tab-pane"),R=a("n-tabs"),W=a("n-layout"),N=a("n-collapse"),V=a("n-scrollbar"),z=a("n-tag"),Z=a("n-layout-sider"),ee=a("n-modal");return c(),F(h,null,[t(E,{title:"\u9AD8\u7EA7\u4E8B\u4EF6\u914D\u7F6E",name:"2"},{"header-extra":e(()=>[t(k,{type:"primary",tertiary:"",size:"small",onClick:_[0]||(_[0]=le(u=>i.value=!0,["stop"]))},{icon:e(()=>[t(w,null,{default:e(()=>[t(r(C))]),_:1})]),default:e(()=>[ye]),_:1})]),default:e(()=>[t(B,null,{default:e(()=>[(c(!0),F(h,null,y(r(D),u=>(c(),F("div",{key:u},[d("p",null,[d("span",Ce,"async "+m(u),1),xe]),d("p",Ae,[t(T,{code:(r(n).events||{})[u],language:"typescript"},null,8,["code"])]),be]))),128))]),_:1})]),_:1}),t(ee,{class:"go-chart-data-monaco-editor",show:i.value,"onUpdate:show":_[2]||(_[2]=u=>i.value=u),"mask-closable":!1},{default:e(()=>[t(B,{bordered:!1,role:"dialog",size:"small","aria-modal":"true",style:{width:"1200px",height:"700px"}},{header:e(()=>[t(L,null,{default:e(()=>[t(p,null,{default:e(()=>[we]),_:1})]),_:1})]),"header-extra":e(()=>[]),action:e(()=>[t(L,{justify:"space-between"},{default:e(()=>[d("div",Ne,[t(z,{bordered:!1,type:"primary"},{icon:e(()=>[t(w,{component:r(j)},null,8,["component"])]),default:e(()=>[Ve]),_:1}),t(p,{class:"go-ml-2",depth:"2"},{default:e(()=>[ze]),_:1})]),t(L,null,{default:e(()=>[t(k,{size:"medium",onClick:P},{default:e(()=>[qe]),_:1}),t(k,{size:"medium",type:"primary",onClick:Y},{default:e(()=>[Pe]),_:1})]),_:1})]),_:1})]),default:e(()=>[t(W,{"has-sider":"","sider-placement":"right"},{default:e(()=>[t(W,{style:{height:"580px","padding-right":"20px"}},{default:e(()=>[t(R,{value:g.value,"onUpdate:value":_[1]||(_[1]=u=>g.value=u),type:"card","tab-style":"min-width: 100px;"},{suffix:e(()=>[t(p,{class:"tab-tip",type:"warning"},{default:e(()=>[o("tips: "+m(A[g.value]),1)]),_:1})]),default:e(()=>[(c(!0),F(h,null,y(r(D),(u,S)=>(c(),O(I,{key:S,tab:`${x[u]}-${u}`,name:u},{default:e(()=>[d("p",ke,[Te,d("span",Se,m(u)+"(e, components, echarts, node_modules)\xA0\xA0{",1)]),t(r(ie),{modelValue:r(v)[u],"onUpdate:modelValue":te=>r(v)[u]=te,height:"480px",language:"javascript"},null,8,["modelValue","onUpdate:modelValue"]),Me]),_:2},1032,["tab","name"]))),128))]),_:1},8,["value"])]),_:1}),t(Z,{"collapsed-width":14,width:340,"show-trigger":"bar","collapse-mode":"transform","content-style":"padding: 12px 12px 0px 12px;margin-left: 3px;"},{default:e(()=>[t(R,{"default-value":"1","justify-content":"space-evenly",type:"segment"},{default:e(()=>[t(I,{tab:"\u9A8C\u8BC1\u7ED3\u679C",name:"1",size:"small"},{default:e(()=>[t(V,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(N,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2,3]},{default:e(()=>[(c(!0),F(h,null,y([q()],u=>(c(),F(h,{key:u},[t(E,{title:"\u9519\u8BEF\u51FD\u6570",name:1},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[o(m(u.errorFn||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(E,{title:"\u9519\u8BEF\u4FE1\u606F",name:2},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[o(m(u.name||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(E,{title:"\u5806\u6808\u4FE1\u606F",name:3},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[o(m(u.message||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024)],64))),128))]),_:1})]),_:1})]),_:1}),t(I,{tab:"\u53D8\u91CF\u8BF4\u660E",name:"2"},{default:e(()=>[t(V,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(N,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2,3,4]},{default:e(()=>[t(E,{title:"e",name:1},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[Oe]),_:1})]),_:1}),t(E,{title:"this",name:2},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[$e]),_:1}),je,(c(!0),F(h,null,y(["refs","setupState","ctx","props","..."],u=>(c(),O(z,{class:"go-m-1",key:u},{default:e(()=>[o(m(u),1)]),_:2},1024))),128))]),_:1}),t(E,{title:"components",name:3},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[Ie]),_:1}),t(T,{code:`{
  [id]: component
}`,language:"typescript"})]),_:1}),t(E,{title:"node_modules",name:4},{default:e(()=>[t(p,{depth:"3"},{default:e(()=>[Ue]),_:1}),Le,(c(!0),F(h,null,y(Object.keys(r(pe)||{}),u=>(c(),O(z,{class:"go-m-1",key:u},{default:e(()=>[o(m(u),1)]),_:2},1024))),128))]),_:1})]),_:1})]),_:1})]),_:1}),t(I,{tab:"\u4ECB\u7ECD\u6848\u4F8B",name:"3"},{default:e(()=>[t(V,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(N,{"arrow-placement":"right"},{default:e(()=>[(c(!0),F(h,null,y(r(ve),(u,S)=>(c(),O(E,{key:S,title:`\u6848\u4F8B${S+1}\uFF1A${u.description}`,name:S},{default:e(()=>[t(T,{code:u.code,language:"typescript"},null,8,["code"])]),_:2},1032,["title","name"]))),128))]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["show"])],64)}}});var We=ae(Re,[["__scopeId","data-v-0c72195a"]]);const Ge=o(" \u7EC4\u4EF6 id\uFF1A "),He={class:"go-event"},Je=o("\u3010\u5355\u51FB\u3001\u53CC\u51FB\u3001\u79FB\u5165\u3001\u79FB\u51FA\u3011\u5728\u5F00\u53D1\u4E2D\uFF0C\u5373\u5C06\u4E0A\u7EBF\uFF01"),Ke=d("br",null,null,-1),Qe=o("\uFF08\u5907\u6CE8\uFF1A\u9AD8\u7EA7\u4E8B\u4EF6\u6A21\u5757\u53EF\u81EA\u884C\u5B9E\u73B0\u4E0A\u8FF0\u529F\u80FD\uFF09"),st=K({__name:"index",setup(l){const{targetData:n}=Q();return M(!1),(s,j)=>{const f=a("n-text"),C=a("n-collapse-item"),x=a("n-collapse");return c(),O(x,{class:"go-mt-3","arrow-placement":"right","default-expanded-names":["1","2"]},{default:e(()=>[t(f,{depth:"3"},{default:e(()=>[Ge,t(f,null,{default:e(()=>[o(m(r(n).id),1)]),_:1})]),_:1}),t(C,{title:"\u57FA\u7840\u4E8B\u4EF6\u914D\u7F6E",name:"1"},{default:e(()=>[d("div",He,[t(f,{depth:"3"},{default:e(()=>[Je]),_:1}),Ke,t(f,{depth:"3"},{default:e(()=>[Qe]),_:1})])]),_:1}),t(r(We))]),_:1})}}});export{st as default};
