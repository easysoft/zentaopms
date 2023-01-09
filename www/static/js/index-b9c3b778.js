var ie=Object.defineProperty;var te=Object.getOwnPropertySymbols;var _e=Object.prototype.hasOwnProperty,pe=Object.prototype.propertyIsEnumerable;var ue=(r,a,i)=>a in r?ie(r,a,{enumerable:!0,configurable:!0,writable:!0,value:i}):r[a]=i,V=(r,a)=>{for(var i in a||(a={}))_e.call(a,i)&&ue(r,i,a[i]);if(te)for(var i of te(a))pe.call(a,i)&&ue(r,i,a[i]);return r};var Y=(r,a,i)=>new Promise((U,$)=>{var k=p=>{try{D(i.next(p))}catch(h){$(h)}},S=p=>{try{D(i.throw(p))}catch(h){$(h)}},D=p=>p.done?U(p.value):Promise.resolve(p.value).then(k,S);D((i=i.apply(r,a)).next())});import{b as ne,d as Z,j as w,L as oe,r as o,o as c,c as F,f as t,w as e,an as ae,u as d,F as y,q as A,p as s,t as m,g as u,E as z,v as se,x as le}from"./index-67a30bc6.js";import{M as ce}from"./EditorWorker-ce739c1a.js";import"./editorWorker-a0599278.js";import{u as ee}from"./useTargetData.hook-8b843344.js";import{i as de}from"./icon-99a136c4.js";import{E as N,B as C}from"./chartEditStore-8254eca3.js";import{n as me}from"./useLifeHandler.hook-edf2bfb6.js";import"./plugin-463a9df8.js";const Ee=`
console.log(e)
`,fe=`
console.log(echarts)
`,Fe=`
console.log(components)
`,he=`
console.log(node_modules)
`,ge=`
// \u5728\u6E32\u67D3\u4E4B\u540E\u624D\u80FD\u83B7\u53D6 dom \u5B9E\u4F8B
e.el.addEventListener('click', () => {
  alert('\u6211\u89E6\u53D1\u62C9~');
}, false)
`,ve=`
await import('https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/lodash.js/4.17.21/lodash.js')

// lodash \u9ED8\u8BA4\u8D4B\u503C\u7ED9 "_"
console.log('isEqual', _.isEqual(['1'], ['1']))
`,Be=`
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
`,ye=`
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
`,xe=[{description:"\u83B7\u53D6\u5F53\u524D\u7EC4\u4EF6\u5B9E\u4F8B",code:Ee},{description:"\u83B7\u53D6\u5168\u5C40 echarts \u5B9E\u4F8B",code:fe},{description:"\u83B7\u53D6\u7EC4\u4EF6\u56FE\u8868\u96C6\u5408",code:Fe},{description:"\u83B7\u53D6 nodeModules \u5B9E\u4F8B",code:he},{description:"\u83B7\u53D6\u8FDC\u7A0B CDN \u5E93",code:ve},{description:"\u8BBE\u7F6E\u6587\u5B57\u7EC4\u4EF6\u70B9\u51FB\u4E8B\u4EF6",code:ge},{description:"\u4FEE\u6539\u56FE\u8868 tooltip",code:Be},{description:"\u6DFB\u52A0\u3010\u8F6E\u64AD\u5217\u8868\u3011\u6837\u5F0F",code:ye},{description:"\u4FEE\u6539\u3010\u5730\u56FE\u3011\u5706\u70B9\uFF0C\u65B0\u589E\u63D0\u793A\u81EA\u52A8\u8F6E\u64AD",code:De}];const P=r=>(se("data-v-b75ed190"),r=r(),le(),r),Ce=u(" \u7F16\u8F91 "),be={class:"func-annotate"},Ae=P(()=>s("br",null,null,-1)),we={class:"func-keyword"},Oe=u(" (e, components, echarts, node_modules) { "),$e={class:"go-ml-4"},ke=P(()=>s("p",null,[u("}"),s("span",null,",")],-1)),Se=u("\u9AD8\u7EA7\u4E8B\u4EF6\u7F16\u8F91\u5668\uFF08\u914D\u5408\u6E90\u7801\u4F7F\u7528\uFF09"),Te={class:"go-pl-3"},Me=P(()=>s("span",{class:"func-keyword"},"async function \xA0\xA0",-1)),Ie={class:"func-keyNameWord"},je=P(()=>s("p",{class:"go-pl-3 func-keyNameWord"},"}",-1)),Ve=u("\u89E6\u53D1\u5BF9\u5E94\u751F\u547D\u5468\u671F\u4E8B\u4EF6\u65F6\u63A5\u6536\u7684\u53C2\u6570"),Ne=u("\u56FE\u8868\u7EC4\u4EF6\u5B9E\u4F8B"),Ue=P(()=>s("br",null,null,-1)),Le=u("\u5F53\u524D\u5927\u5C4F\u5185\u6240\u6709\u7EC4\u4EF6\u7684\u96C6\u5408id \u56FE\u8868\u7EC4\u4EF6\u4E2D\u7684\u914D\u7F6Eid\uFF0C\u53EF\u4EE5\u83B7\u53D6\u5176\u4ED6\u56FE\u8868\u7EC4\u4EF6\u8FDB\u884C\u63A7\u5236"),ze=u("\u4EE5\u4E0B\u662F\u5185\u7F6E\u5728\u4EE3\u7801\u73AF\u5883\u4E2D\u53EF\u7528\u7684\u5305\u53D8\u91CF"),Pe=P(()=>s("br",null,null,-1)),Re={class:"go-flex-items-center"},Ke=u(" \u8BF4\u660E "),qe=u("\u901A\u8FC7\u63D0\u4F9B\u7684\u53C2\u6570\u53EF\u4E3A\u56FE\u8868\u589E\u52A0\u5B9A\u5236\u5316\u7684tooltip\u3001\u4EA4\u4E92\u4E8B\u4EF6\u7B49\u7B49"),We=u("\u53D6\u6D88"),He=u("\u4FDD\u5B58"),Je=Z({__name:"index",setup(r){const{targetData:a,chartEditStore:i}=ee(),{DocumentTextIcon:U,ChevronDownIcon:$,PencilIcon:k}=de.ionicons5,S={[N.VNODE_BEFORE_MOUNT]:"\u6E32\u67D3\u4E4B\u524D",[N.VNODE_MOUNTED]:"\u6E32\u67D3\u4E4B\u540E"},D={[N.VNODE_BEFORE_MOUNT]:"\u6B64\u65F6\u7EC4\u4EF6 DOM \u8FD8\u672A\u5B58\u5728",[N.VNODE_MOUNTED]:"\u6B64\u65F6\u7EC4\u4EF6 DOM \u5DF2\u7ECF\u5B58\u5728"},p=w(!1),h=w(N.VNODE_MOUNTED);let O=w(V({},a.value.events.advancedEvents));const J=w(!1),R=()=>{let E="",f="",x="";return J.value=Object.entries(O.value).every(([b,v])=>{try{const B=Object.getPrototypeOf(function(){return Y(this,null,function*(){})}).constructor;return new B(v),!0}catch(B){return f=B.message,x=B.name,E=b,!1}}),{errorFn:E,message:f,name:x}},G=()=>{p.value=!1},T=()=>{if(R().errorFn){window.$message.error("\u4E8B\u4EF6\u51FD\u6570\u9519\u8BEF\uFF0C\u65E0\u6CD5\u8FDB\u884C\u4FDD\u5B58");return}Object.values(O.value).join("").trim()===""?a.value.events.advancedEvents={vnodeBeforeMount:void 0,vnodeMounted:void 0}:a.value.events.advancedEvents=V({},O.value),G()};return oe(()=>p.value,E=>{E&&(O.value=V({},a.value.events.advancedEvents))}),(E,f)=>{const x=o("n-icon"),b=o("n-button"),v=o("n-code"),B=o("n-card"),_=o("n-collapse-item"),g=o("n-text"),M=o("n-space"),I=o("n-tab-pane"),K=o("n-tabs"),q=o("n-layout"),L=o("n-collapse"),W=o("n-scrollbar"),H=o("n-tag"),X=o("n-layout-sider"),l=o("n-modal");return c(),F(y,null,[t(_,{title:"\u9AD8\u7EA7\u4E8B\u4EF6\u914D\u7F6E",name:"2"},{"header-extra":e(()=>[t(b,{type:"primary",tertiary:"",size:"small",onClick:f[0]||(f[0]=ae(n=>p.value=!0,["stop"]))},{icon:e(()=>[t(x,null,{default:e(()=>[t(d(k))]),_:1})]),default:e(()=>[Ce]),_:1})]),default:e(()=>[t(B,{class:"collapse-show-box"},{default:e(()=>[(c(!0),F(y,null,A(d(N),n=>(c(),F("div",{key:n},[s("p",null,[s("span",be,"// "+m(S[n]),1),Ae,s("span",we,"async "+m(n),1),Oe]),s("p",$e,[t(v,{code:(d(a).events.advancedEvents||{})[n]||"",language:"typescript"},null,8,["code"])]),ke]))),128))]),_:1})]),_:1}),t(l,{class:"go-chart-data-monaco-editor",show:p.value,"onUpdate:show":f[2]||(f[2]=n=>p.value=n),"mask-closable":!1},{default:e(()=>[t(B,{bordered:!1,role:"dialog",size:"small","aria-modal":"true",style:{width:"1200px",height:"700px"}},{header:e(()=>[t(M,null,{default:e(()=>[t(g,null,{default:e(()=>[Se]),_:1})]),_:1})]),"header-extra":e(()=>[]),action:e(()=>[t(M,{justify:"space-between"},{default:e(()=>[s("div",Re,[t(H,{bordered:!1,type:"primary"},{icon:e(()=>[t(x,{component:d(U)},null,8,["component"])]),default:e(()=>[Ke]),_:1}),t(g,{class:"go-ml-2",depth:"2"},{default:e(()=>[qe]),_:1})]),t(M,null,{default:e(()=>[t(b,{size:"medium",onClick:G},{default:e(()=>[We]),_:1}),t(b,{size:"medium",type:"primary",onClick:T},{default:e(()=>[He]),_:1})]),_:1})]),_:1})]),default:e(()=>[t(q,{"has-sider":"","sider-placement":"right"},{default:e(()=>[t(q,{style:{height:"580px","padding-right":"20px"}},{default:e(()=>[t(K,{value:h.value,"onUpdate:value":f[1]||(f[1]=n=>h.value=n),type:"card","tab-style":"min-width: 100px;"},{suffix:e(()=>[t(g,{class:"tab-tip",type:"warning"},{default:e(()=>[u("\u63D0\u793A: "+m(D[h.value]),1)]),_:1})]),default:e(()=>[(c(!0),F(y,null,A(d(N),(n,j)=>(c(),z(I,{key:j,tab:`${S[n]}-${n}`,name:n},{default:e(()=>[s("p",Te,[Me,s("span",Ie,m(n)+"(e, components, echarts, node_modules)\xA0\xA0{",1)]),t(d(ce),{modelValue:d(O)[n],"onUpdate:modelValue":re=>d(O)[n]=re,height:"480px",language:"javascript"},null,8,["modelValue","onUpdate:modelValue"]),je]),_:2},1032,["tab","name"]))),128))]),_:1},8,["value"])]),_:1}),t(X,{"collapsed-width":14,width:340,"show-trigger":"bar","collapse-mode":"transform","content-style":"padding: 12px 12px 0px 12px;margin-left: 3px;"},{default:e(()=>[t(K,{"default-value":"1","justify-content":"space-evenly",type:"segment"},{default:e(()=>[t(I,{tab:"\u9A8C\u8BC1\u7ED3\u679C",name:"1",size:"small"},{default:e(()=>[t(W,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(L,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2,3]},{default:e(()=>[(c(!0),F(y,null,A([R()],n=>(c(),F(y,{key:n},[t(_,{title:"\u9519\u8BEF\u51FD\u6570",name:1},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[u(m(n.errorFn||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(_,{title:"\u9519\u8BEF\u4FE1\u606F",name:2},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[u(m(n.name||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(_,{title:"\u5806\u6808\u4FE1\u606F",name:3},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[u(m(n.message||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024)],64))),128))]),_:1})]),_:1})]),_:1}),t(I,{tab:"\u53D8\u91CF\u8BF4\u660E",name:"2"},{default:e(()=>[t(W,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(L,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2,3,4]},{default:e(()=>[t(_,{title:"e",name:1},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[Ve]),_:1})]),_:1}),t(_,{title:"this",name:2},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[Ne]),_:1}),Ue,(c(!0),F(y,null,A(["refs","setupState","ctx","props","..."],n=>(c(),z(H,{class:"go-m-1",key:n},{default:e(()=>[u(m(n),1)]),_:2},1024))),128))]),_:1}),t(_,{title:"components",name:3},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[Le]),_:1}),t(v,{code:`{
  [id]: component
}`,language:"typescript"})]),_:1}),t(_,{title:"node_modules",name:4},{default:e(()=>[t(g,{depth:"3"},{default:e(()=>[ze]),_:1}),Pe,(c(!0),F(y,null,A(Object.keys(d(me)||{}),n=>(c(),z(H,{class:"go-m-1",key:n},{default:e(()=>[u(m(n),1)]),_:2},1024))),128))]),_:1})]),_:1})]),_:1})]),_:1}),t(I,{tab:"\u4ECB\u7ECD\u6848\u4F8B",name:"3"},{default:e(()=>[t(W,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(L,{"arrow-placement":"right"},{default:e(()=>[(c(!0),F(y,null,A(d(xe),(n,j)=>(c(),z(_,{key:j,title:`\u6848\u4F8B${j+1}\uFF1A${n.description}`,name:j},{default:e(()=>[t(v,{code:n.code,language:"typescript"},null,8,["code"])]),_:2},1032,["title","name"]))),128))]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["show"])],64)}}});var Ge=ne(Je,[["__scopeId","data-v-b75ed190"]]);const Q=r=>(se("data-v-cd98b5a6"),r=r(),le(),r),Qe=u(" \u7F16\u8F91 "),Xe={class:"func-annotate"},Ye=Q(()=>s("br",null,null,-1)),Ze={class:"func-keyword"},et=u(" (mouseEvent) { "),tt={class:"go-ml-4"},ut=Q(()=>s("p",null,[u("}"),s("span",null,",")],-1)),nt=u("\u57FA\u7840\u4E8B\u4EF6\u7F16\u8F91\u5668"),ot=u("\u63D0\u793A: ECharts \u7EC4\u4EF6\u4F1A\u62E6\u622A\u9F20\u6807\u4E8B\u4EF6"),at={class:"go-pl-3"},st=Q(()=>s("span",{class:"func-keyword"},"async function \xA0\xA0",-1)),lt={class:"func-keyNameWord"},ct=Q(()=>s("p",{class:"go-pl-3 func-keyNameWord"},"}",-1)),dt=u("\u9F20\u6807\u4E8B\u4EF6\u5BF9\u8C61"),rt={class:"go-flex-items-center"},it=u(" \u8BF4\u660E "),_t=u("\u7F16\u5199\u65B9\u5F0F\u540C\u6B63\u5E38 JavaScript \u5199\u6CD5"),pt=u("\u53D6\u6D88"),mt=u("\u4FDD\u5B58"),Et=Z({__name:"index",setup(r){const{targetData:a,chartEditStore:i}=ee(),{DocumentTextIcon:U,ChevronDownIcon:$,PencilIcon:k}=de.ionicons5,S={[C.ON_CLICK]:"\u5355\u51FB",[C.ON_DBL_CLICK]:"\u53CC\u51FB",[C.ON_MOUSE_ENTER]:"\u9F20\u6807\u8FDB\u5165",[C.ON_MOUSE_LEAVE]:"\u9F20\u6807\u79FB\u51FA"},D=w(!1),p=w(C.ON_CLICK);let h=w(V({},a.value.events.baseEvent));const O=w(!1),J=()=>{let T="",E="",f="";return O.value=Object.entries(h.value).every(([x,b])=>{try{const v=Object.getPrototypeOf(function(){return Y(this,null,function*(){})}).constructor;return new v(b),!0}catch(v){return E=v.message,f=v.name,T=x,!1}}),{errorFn:T,message:E,name:f}},R=()=>{D.value=!1},G=()=>{if(J().errorFn){window.$message.error("\u4E8B\u4EF6\u51FD\u6570\u9519\u8BEF\uFF0C\u65E0\u6CD5\u8FDB\u884C\u4FDD\u5B58");return}Object.values(h.value).join("").trim()===""?a.value.events.baseEvent={[C.ON_CLICK]:void 0,[C.ON_DBL_CLICK]:void 0,[C.ON_MOUSE_ENTER]:void 0,[C.ON_MOUSE_LEAVE]:void 0}:a.value.events.baseEvent=V({},h.value),R()};return oe(()=>D.value,T=>{T&&(h.value=V({},a.value.events.baseEvent))}),(T,E)=>{const f=o("n-icon"),x=o("n-button"),b=o("n-code"),v=o("n-card"),B=o("n-collapse-item"),_=o("n-text"),g=o("n-space"),M=o("n-tab-pane"),I=o("n-tabs"),K=o("n-layout"),q=o("n-collapse"),L=o("n-scrollbar"),W=o("n-layout-sider"),H=o("n-tag"),X=o("n-modal");return c(),F(y,null,[t(B,{title:"\u57FA\u7840\u4E8B\u4EF6\u914D\u7F6E",name:"1"},{"header-extra":e(()=>[t(x,{type:"primary",tertiary:"",size:"small",onClick:E[0]||(E[0]=ae(l=>D.value=!0,["stop"]))},{icon:e(()=>[t(f,null,{default:e(()=>[t(d(k))]),_:1})]),default:e(()=>[Qe]),_:1})]),default:e(()=>[t(v,{class:"collapse-show-box"},{default:e(()=>[(c(!0),F(y,null,A(d(C),l=>(c(),F("div",{key:l},[s("p",null,[s("span",Xe,"// "+m(S[l]),1),Ye,s("span",Ze,"async "+m(l),1),et]),s("p",tt,[t(b,{code:(d(a).events.baseEvent||{})[l]||"",language:"typescript"},null,8,["code"])]),ut]))),128))]),_:1})]),_:1}),t(X,{class:"go-chart-data-monaco-editor",show:D.value,"onUpdate:show":E[2]||(E[2]=l=>D.value=l),"mask-closable":!1},{default:e(()=>[t(v,{bordered:!1,role:"dialog",size:"small","aria-modal":"true",style:{width:"1200px",height:"700px"}},{header:e(()=>[t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[nt]),_:1})]),_:1})]),"header-extra":e(()=>[]),action:e(()=>[t(g,{justify:"space-between"},{default:e(()=>[s("div",rt,[t(H,{bordered:!1,type:"primary"},{icon:e(()=>[t(f,{component:d(U)},null,8,["component"])]),default:e(()=>[it]),_:1}),t(_,{class:"go-ml-2",depth:"2"},{default:e(()=>[_t]),_:1})]),t(g,null,{default:e(()=>[t(x,{size:"medium",onClick:R},{default:e(()=>[pt]),_:1}),t(x,{size:"medium",type:"primary",onClick:G},{default:e(()=>[mt]),_:1})]),_:1})]),_:1})]),default:e(()=>[t(K,{"has-sider":"","sider-placement":"right"},{default:e(()=>[t(K,{style:{height:"580px","padding-right":"20px"}},{default:e(()=>[t(I,{value:p.value,"onUpdate:value":E[1]||(E[1]=l=>p.value=l),type:"card","tab-style":"min-width: 100px;"},{suffix:e(()=>[t(_,{class:"tab-tip",type:"warning"},{default:e(()=>[ot]),_:1})]),default:e(()=>[(c(!0),F(y,null,A(d(C),(l,n)=>(c(),z(M,{key:n,tab:`${S[l]}-${l}`,name:l},{default:e(()=>[s("p",at,[st,s("span",lt,m(l)+"(mouseEvent)\xA0\xA0{",1)]),t(d(ce),{modelValue:d(h)[l],"onUpdate:modelValue":j=>d(h)[l]=j,height:"480px",language:"javascript"},null,8,["modelValue","onUpdate:modelValue"]),ct]),_:2},1032,["tab","name"]))),128))]),_:1},8,["value"])]),_:1}),t(W,{"collapsed-width":14,width:340,"show-trigger":"bar","collapse-mode":"transform","content-style":"padding: 12px 12px 0px 12px;margin-left: 3px;"},{default:e(()=>[t(I,{"default-value":"1","justify-content":"space-evenly",type:"segment"},{default:e(()=>[t(M,{tab:"\u9A8C\u8BC1\u7ED3\u679C",name:"1",size:"small"},{default:e(()=>[t(L,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(q,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2,3]},{default:e(()=>[(c(!0),F(y,null,A([J()],l=>(c(),F(y,{key:l},[t(B,{title:"\u9519\u8BEF\u51FD\u6570",name:1},{default:e(()=>[t(_,{depth:"3"},{default:e(()=>[u(m(l.errorFn||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(B,{title:"\u9519\u8BEF\u4FE1\u606F",name:2},{default:e(()=>[t(_,{depth:"3"},{default:e(()=>[u(m(l.name||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024),t(B,{title:"\u5806\u6808\u4FE1\u606F",name:3},{default:e(()=>[t(_,{depth:"3"},{default:e(()=>[u(m(l.message||"\u6682\u65E0"),1)]),_:2},1024)]),_:2},1024)],64))),128))]),_:1})]),_:1})]),_:1}),t(M,{tab:"\u53D8\u91CF\u8BF4\u660E",name:"2"},{default:e(()=>[t(L,{trigger:"none",style:{"max-height":"505px"}},{default:e(()=>[t(q,{class:"go-px-3","arrow-placement":"right","default-expanded-names":[1,2]},{default:e(()=>[t(B,{title:"mouseEvent",name:1},{default:e(()=>[t(_,{depth:"3"},{default:e(()=>[dt]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1},8,["show"])],64)}}});var ft=ne(Et,[["__scopeId","data-v-cd98b5a6"]]);const Ft=u(" \u7EC4\u4EF6 id\uFF1A "),At=Z({__name:"index",setup(r){const{targetData:a}=ee();return w(!1),(i,U)=>{const $=o("n-text"),k=o("n-collapse");return c(),z(k,{class:"go-mt-3","arrow-placement":"right","default-expanded-names":["1","2"]},{default:e(()=>[t($,{depth:"3"},{default:e(()=>[Ft,t($,null,{default:e(()=>[u(m(d(a).id),1)]),_:1})]),_:1}),t(d(ft)),t(d(Ge))]),_:1})}}});export{At as default};
