import{s as b,r as h,o,b as r,w as e,n as t,z as n,C as g,p,P as V,c as x,F as y,a as C,q as v,l as d,x as m}from"./main-e2e587ad.js";import{V as S}from"./VMenu-361b7835.js";import{V as A,a as I,b as k}from"./VCard-d56c746a.js";import{V as w}from"./VDivider-6a4cbbda.js";import{V as B,a as z}from"./VRow-d0c8a337.js";import{V as D}from"./VAvatar-59d5fe07.js";import"./forwardRefs-b6befefd.js";import"./transition-bf1e6868.js";import"./VImg-8c8e46ee.js";const N={class:"text-base font-weight-medium mt-2 mb-0"},q={class:"text-sm"},F={__name:"Shortcuts",props:{togglerIcon:{type:String,required:!1,default:"tabler-layout-grid-add"},shortcuts:{type:Array,required:!0}},setup(l){const s=l,c=b();return(_,i)=>{const u=h("IconBtn");return o(),r(u,null,{default:e(()=>[t(n,{size:"26",icon:s.togglerIcon},null,8,["icon"]),t(S,{activator:"parent",offset:"14px",location:"bottom end"},{default:e(()=>[t(A,{width:"340","max-height":"560",class:"d-flex flex-column"},{default:e(()=>[t(I,{class:"py-4"},{append:e(()=>[t(u,null,{default:e(()=>[t(n,{icon:"tabler-layout-grid-add"})]),_:1})]),default:e(()=>[t(k,null,{default:e(()=>[g("Shortcuts")]),_:1})]),_:1}),t(w),t(p(V),{options:{wheelPropagation:!1}},{default:e(()=>[t(B,{class:"ma-0 mt-n1"},{default:e(()=>[(o(!0),x(y,null,C(s.shortcuts,(a,f)=>(o(),r(z,{key:a.title,cols:"6",class:v(["text-center border-t cursor-pointer pa-4 shortcut-icon",(f+1)%2?"border-e":""]),onClick:M=>p(c).push(a.to)},{default:e(()=>[t(D,{variant:"tonal",size:"48"},{default:e(()=>[t(n,{icon:a.icon},null,8,["icon"])]),_:2},1024),d("h6",N,m(a.title),1),d("span",q,m(a.subtitle),1)]),_:2},1032,["class","onClick"]))),128))]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})}}},j={__name:"NavbarShortcuts",setup(l){const s=[{icon:"tabler-calendar",title:"Calendar",subtitle:"Appointments",to:{name:"apps-calendar"}},{icon:"tabler-file",title:"Invoice App",subtitle:"Manage Accounts",to:{name:"apps-invoice-list"}},{icon:"tabler-user",title:"Users",subtitle:"Manage Users",to:{name:"apps-user-list"}},{icon:"tabler-layout",title:"Dashboard",subtitle:"Dashboard Analytics",to:{name:"dashboards-analytics"}},{icon:"tabler-settings",title:"Settings",subtitle:"Account Settings",to:{name:"pages-account-settings-tab",params:{tab:"account"}}},{icon:"tabler-help",title:"Help Center",subtitle:"FAQs & Articles",to:{name:"pages-help-center"}}];return(c,_)=>{const i=F;return o(),r(i,{shortcuts:s})}}};export{j as default};
