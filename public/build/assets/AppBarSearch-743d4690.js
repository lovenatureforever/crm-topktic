import{B as P,bp as j,j as m,bq as f,D as A,U as E,r as J,o as l,b as g,w as t,n as a,p as r,E as O,ab as B,l as o,z as u,P as X,I as D,c as d,F as v,a as x,C as z,x as b,m as w,J as I,A as L,Y,Z}from"./main-e2e587ad.js";import{V as G,d as $}from"./VCard-d56c746a.js";import{V as H}from"./VTextField-45812526.js";import{V as W}from"./VDivider-6a4cbbda.js";import{V as R,e as ee,a as K,b as se}from"./VList-c2e41445.js";import{V as te,a as ae}from"./VRow-d0c8a337.js";import{V as re}from"./VDialog-76cb5de6.js";import"./VAvatar-59d5fe07.js";import"./VImg-8c8e46ee.js";import"./transition-bf1e6868.js";import"./forwardRefs-b6befefd.js";const Q=_=>(Y("data-v-1a7e993d"),_=_(),Z(),_),le={class:"d-flex align-center text-high-emphasis me-1"},oe={class:"d-flex align-center"},ie={class:"h-100"},ne={class:"text-xs text-disabled text-uppercase"},ce={class:"h-100"},ue={class:"app-bar-search-suggestions d-flex flex-column align-center justify-center text-high-emphasis h-100"},de={class:"d-flex align-center flex-wrap justify-center gap-2 text-h6 my-3"},pe=Q(()=>o("span",null,"No Result For ",-1)),he={key:0,class:"mt-8"},fe=Q(()=>o("span",{class:"d-flex justify-center text-disabled"},"Try searching for",-1)),_e=["onClick"],ye={class:"text-sm"},me={__name:"AppBarSearch",props:{isDialogVisible:{type:Boolean,required:!0},searchQuery:{type:String,required:!0},searchResults:{type:Array,required:!0},suggestions:{type:Array,required:!1},noDataSuggestion:{type:Array,required:!1}},emits:["update:isDialogVisible","update:searchQuery","itemSelected"],setup(_,{emit:y}){const i=_,{ctrl_k:F,meta_k:T}=j({passive:!1,onEventFired(e){e.ctrlKey&&e.key==="k"&&e.type==="keydown"&&e.preventDefault()}}),S=m(),n=m(structuredClone(f(i.searchQuery))),U=m(),C=m(structuredClone(f(i.isDialogVisible))),p=m(structuredClone(f(i.searchResults)));A(i,()=>{C.value=structuredClone(f(i.isDialogVisible)),p.value=structuredClone(f(i.searchResults)),n.value=structuredClone(f(i.searchQuery))}),A([F,T],()=>{C.value=!0,y("update:isDialogVisible",!0)});const V=()=>{y("update:isDialogVisible",!1),y("update:searchQuery","")};E(()=>{n.value.length||(p.value=[])});const q=e=>{var c,k;e.key==="ArrowDown"?(e.preventDefault(),(c=S.value)==null||c.focus("next")):e.key==="ArrowUp"&&(e.preventDefault(),(k=S.value)==null||k.focus("prev"))},M=e=>{y("update:isDialogVisible",e),y("update:searchQuery","")},N=e=>e==="dashboards"?"Dashboards":e==="appsPages"?"Apps & Pages":e==="userInterface"?"User Interface":e==="formsTables"?"Forms Tables":e==="chartsMisc"?"Charts Misc":"Misc";return(e,c)=>{const k=J("IconBtn");return l(),g(re,{"max-width":"600","model-value":r(C),height:e.$vuetify.display.smAndUp?"550":"100%",fullscreen:e.$vuetify.display.width<600,class:"app-bar-search-dialog","onUpdate:modelValue":M,onKeyup:B(V,["esc"])},{default:t(()=>[a(G,{height:"100%",width:"100%",class:"position-relative"},{default:t(()=>[a($,{class:"pt-1",style:{"min-block-size":"65px"}},{default:t(()=>[a(H,{ref_key:"refSearchInput",ref:U,modelValue:r(n),"onUpdate:modelValue":[c[0]||(c[0]=s=>O(n)?n.value=s:null),c[1]||(c[1]=s=>e.$emit("update:searchQuery",r(n)))],autofocus:"",density:"comfortable",variant:"plain",class:"app-bar-autocomplete-box",onKeyup:B(V,["esc"]),onKeydown:q},{"prepend-inner":t(()=>[o("div",le,[a(u,{size:"22",icon:"tabler-search",class:"mt-1",style:{opacity:"1"}})])]),"append-inner":t(()=>[o("div",oe,[o("div",{class:"text-base text-disabled cursor-pointer me-1",onClick:V}," [esc] "),a(k,{size:"small",onClick:V},{default:t(()=>[a(u,{icon:"tabler-x"})]),_:1})])]),_:1},8,["modelValue","onKeyup"])]),_:1}),a(W),a(r(X),{options:{wheelPropagation:!1,suppressScrollX:!0},class:"h-100"},{default:t(()=>[D(a(r(R),{ref_key:"refSearchList",ref:S,density:"compact",class:"app-bar-search-list"},{default:t(()=>[(l(!0),d(v,null,x(r(p),s=>(l(),d(v,{key:s.title},["header"in s?(l(),g(r(ee),{key:0,class:"text-disabled"},{default:t(()=>[z(b(N(s.title)),1)]),_:2},1024)):w(e.$slots,"searchResult",{key:1,item:s},()=>[a(r(K),{link:"",onClick:h=>e.$emit("itemSelected",s)},{prepend:t(()=>[a(u,{size:"20",icon:s.icon,class:"me-3"},null,8,["icon"])]),append:t(()=>[a(u,{size:"20",icon:"tabler-corner-down-left",class:"enter-icon text-disabled"})]),default:t(()=>[a(se,null,{default:t(()=>[z(b(s.title),1)]),_:2},1024)]),_:2},1032,["onClick"])],!0)],64))),128))]),_:3},512),[[I,r(n).length&&!!r(p).length]]),D(o("div",ie,[w(e.$slots,"suggestions",{},()=>[a($,{class:"app-bar-search-suggestions h-100 pa-10"},{default:t(()=>[i.suggestions?(l(),g(te,{key:0,class:"gap-y-4"},{default:t(()=>[(l(!0),d(v,null,x(i.suggestions,s=>(l(),g(ae,{key:s.title,cols:"12",sm:"6",class:"ps-6"},{default:t(()=>[o("p",ne,b(s.title),1),a(r(R),{class:"card-list"},{default:t(()=>[(l(!0),d(v,null,x(s.content,h=>(l(),g(r(K),{key:h.title,link:"",title:h.title,class:"app-bar-search-suggestion",onClick:ge=>e.$emit("itemSelected",h)},{prepend:t(()=>[a(u,{icon:h.icon,size:"20",class:"me-2"},null,8,["icon"])]),_:2},1032,["title","onClick"]))),128))]),_:2},1024)]),_:2},1024))),128))]),_:1})):L("",!0)]),_:1})],!0)],512),[[I,!!r(p)&&!r(n)]]),D(o("div",ce,[w(e.$slots,"noData",{},()=>[a($,{class:"h-100"},{default:t(()=>[o("div",ue,[a(u,{size:"75",icon:"tabler-file-x"}),o("div",de,[pe,o("span",null,'"'+b(r(n))+'"',1)]),i.noDataSuggestion?(l(),d("div",he,[fe,(l(!0),d(v,null,x(i.noDataSuggestion,s=>(l(),d("h6",{key:s.title,class:"app-bar-search-suggestion text-sm font-weight-regular cursor-pointer mt-3",onClick:h=>e.$emit("itemSelected",s)},[a(u,{size:"20",icon:s.icon,class:"me-3"},null,8,["icon"]),o("span",ye,b(s.title),1)],8,_e))),128))])):L("",!0)])]),_:1})],!0)],512),[[I,!r(p).length&&r(n).length]])]),_:3})]),_:3})]),_:3},8,["model-value","height","fullscreen","onKeyup"])}}},Ae=P(me,[["__scopeId","data-v-1a7e993d"]]);export{Ae as default};
