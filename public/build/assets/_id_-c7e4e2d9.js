import{m as Se,b as ke,u as ye,c as ne,f as Ce,d as De,e as Ue,a as we}from"./VTextField-93f4669b.js";import{N as Re,Q as Ie,G as A,j as _,a2 as le,D as M,ad as Pe,S as he,a6 as Ne,n as e,H as ee,F as q,I as ue,ae as $e,af as Ae,a7 as ae,a8 as Fe,ag as Be,ah as Me,d as He,ai as re,o as L,c as me,p as a,b as K,A as ce,aa as Te,a as fe,w as s,m as je,aj as ie,ak as de,q as Le,a1 as ze,L as O,C as D,E as Y,x as j,l as $,s as Ee,al as Ge}from"./main-b50d5463.js";import{I as Oe}from"./VImg-baea078e.js";import{f as Je}from"./forwardRefs-147dc27a.js";import{_ as pe}from"./AppSelect-c7883347.js";import{V as te,_ as qe}from"./VSnackbar-bfdea7fe.js";import{_ as Ke}from"./DialogCloseBtn-b532f150.js";import{k as Ye,s as V,G as Qe,l as We,Y as Xe,f as Ze}from"./auth-ec437199.js";import{b as G,a as J}from"./axios-c835c00c.js";import{V as ge,d as Q,b as ea,a as aa}from"./VCard-b32c692a.js";import{V as S,a as f}from"./VRow-4cca22a4.js";import{V as ta}from"./VDialog-b724fadf.js";import{V as la}from"./VDivider-43ab8d07.js";import"./transition-a497af79.js";import"./VSelect-0e8168f5.js";import"./VList-efbd3225.js";import"./VAvatar-8d1777e9.js";import"./VMenu-bec20866.js";import"./VCheckboxBtn-3b1c30a5.js";const oa=Re()({name:"VTextarea",directives:{Intersect:Oe},inheritAttrs:!1,props:{autoGrow:Boolean,autofocus:Boolean,counter:[Boolean,Number,String],counterValue:Function,prefix:String,placeholder:String,persistentPlaceholder:Boolean,persistentCounter:Boolean,noResize:Boolean,rows:{type:[Number,String],default:5,validator:n=>!isNaN(parseFloat(n))},maxRows:{type:[Number,String],validator:n=>!isNaN(parseFloat(n))},suffix:String,modelModifiers:Object,...Se(),...ke()},emits:{"click:control":n=>!0,"mousedown:control":n=>!0,"update:focused":n=>!0,"update:modelValue":n=>!0},setup(n,k){let{attrs:U,emit:v,slots:d}=k;const l=Ie(n,"modelValue"),{isFocused:b,focus:C,blur:y}=ye(n),R=A(()=>typeof n.counterValue=="function"?n.counterValue(l.value):(l.value||"").toString().length),w=A(()=>{if(U.maxlength)return U.maxlength;if(!(!n.counter||typeof n.counter!="number"&&typeof n.counter!="string"))return n.counter});function h(r,m){var x,P;!n.autofocus||!r||(P=(x=m[0].target)==null?void 0:x.focus)==null||P.call(x)}const F=_(),N=_(),H=_(""),I=_(),T=A(()=>b.value||n.persistentPlaceholder);function B(){var r;I.value!==document.activeElement&&((r=I.value)==null||r.focus()),b.value||C()}function i(r){B(),v("click:control",r)}function o(r){v("mousedown:control",r)}function c(r){r.stopPropagation(),B(),ae(()=>{l.value="",Fe(n["onClick:clear"],r)})}function g(r){var x;const m=r.target;if(l.value=m.value,(x=n.modelModifiers)!=null&&x.trim){const P=[m.selectionStart,m.selectionEnd];ae(()=>{m.selectionStart=P[0],m.selectionEnd=P[1]})}}const u=_();function t(){n.autoGrow&&ae(()=>{if(!u.value||!N.value)return;const r=getComputedStyle(u.value),m=getComputedStyle(N.value.$el),x=parseFloat(r.getPropertyValue("--v-field-padding-top"))+parseFloat(r.getPropertyValue("--v-input-padding-top"))+parseFloat(r.getPropertyValue("--v-field-padding-bottom")),P=u.value.scrollHeight,W=parseFloat(r.lineHeight),X=Math.max(parseFloat(n.rows)*W+x,parseFloat(m.getPropertyValue("--v-input-control-height"))),Z=parseFloat(n.maxRows)*W+x||1/0;H.value=Be(Me(P??0,X,Z))})}le(t),M(l,t),M(()=>n.rows,t),M(()=>n.maxRows,t),M(()=>n.density,t);let p;return M(u,r=>{r?(p=new ResizeObserver(t),p.observe(u.value)):p==null||p.disconnect()}),Pe(()=>{p==null||p.disconnect()}),he(()=>{const r=!!(d.counter||n.counter||n.counterValue),m=!!(r||d.details),[x,P]=Ne(U),[{modelValue:W,...X}]=ne.filterProps(n),[Z]=Ce(n);return e(ne,ee({ref:F,modelValue:l.value,"onUpdate:modelValue":z=>l.value=z,class:["v-textarea v-text-field",{"v-textarea--prefixed":n.prefix,"v-textarea--suffixed":n.suffix,"v-text-field--prefixed":n.prefix,"v-text-field--suffixed":n.suffix,"v-textarea--auto-grow":n.autoGrow,"v-textarea--no-resize":n.noResize||n.autoGrow,"v-text-field--flush-details":["plain","underlined"].includes(n.variant)},n.class],style:n.style},x,X,{focused:b.value}),{...d,default:z=>{let{isDisabled:E,isDirty:oe,isReadonly:ve,isValid:Ve}=z;return e(De,ee({ref:N,style:{"--v-textarea-control-height":H.value},onClick:i,onMousedown:o,"onClick:clear":c,"onClick:prependInner":n["onClick:prependInner"],"onClick:appendInner":n["onClick:appendInner"],role:"textbox"},Z,{active:T.value||oe.value,dirty:oe.value||n.dirty,disabled:E.value,focused:b.value,error:Ve.value===!1}),{...d,default:be=>{let{props:{class:se,..._e}}=be;return e(q,null,[n.prefix&&e("span",{class:"v-text-field__prefix"},[n.prefix]),ue(e("textarea",ee({ref:I,class:se,value:l.value,onInput:g,autofocus:n.autofocus,readonly:ve.value,disabled:E.value,placeholder:n.placeholder,rows:n.rows,name:n.name,onFocus:B,onBlur:y},_e,P),null),[[$e("intersect"),{handler:h},null,{once:!0}]]),n.autoGrow&&ue(e("textarea",{class:[se,"v-textarea__sizer"],"onUpdate:modelValue":xe=>l.value=xe,ref:u,readonly:!0,"aria-hidden":"true"},null),[[Ae,l.value]]),n.suffix&&e("span",{class:"v-text-field__suffix"},[n.suffix])])}})},details:m?z=>{var E;return e(q,null,[(E=d.details)==null?void 0:E.call(d,z),r&&e(q,null,[e("span",null,null),e(Ue,{active:n.persistentCounter||b.value,value:R.value,max:w.value},d.counter)])])}:void 0})}),Je({},F,N,I)}}),sa=He({name:"AppTextarea",inheritAttrs:!1}),na=Object.assign(sa,{setup(n){const k=A(()=>{const v=re(),d=v.id||v.label;return d?`app-textarea-${d}-${Math.random().toString(36).slice(2,7)}`:void 0}),U=A(()=>re().label);return(v,d)=>(L(),me("div",{class:Le(["app-textarea flex-grow-1",v.$attrs.class])},[a(U)?(L(),K(we,{key:0,for:a(k),class:"mb-1 text-body-2 text-high-emphasis",text:a(U)},null,8,["for","text"])):ce("",!0),e(oa,ie(de({...v.$attrs,class:null,label:void 0,variant:"outlined",id:a(k)})),Te({_:2},[fe(v.$slots,(l,b)=>({name:b,fn:s(C=>[je(v.$slots,b,ie(de(C||{})))])}))]),1040)],2))}}),ua={__name:"SubmitDialog",props:{isDialogVisible:{type:Boolean,required:!0}},emits:["close-dialog","update-record"],setup(n,{emit:k}){const U=n,v="https://ktic.com.my/api",d=ze(U,"isDialogVisible"),l=_(Ye),b=_([]),C=_([]),y=_([]),R=_([]),w=A(()=>h.value.length>0),h=_(""),F=()=>{k("close-dialog")};M(()=>l.value.product,(i,o)=>{i!=o&&T()},{deep:!0}),M(()=>l.value.state,(i,o)=>{i!=o&&H()},{deep:!0});const N=()=>{const i={api_id:"SP202407CRM",api_token:"vdsas3DDVv12j4vNSCJ435fgdhghGDS890cvbH1"};G.get(`${v}/api_state_list.php`,{params:i}).then(o=>{const c=o.data;c.error?b.value=[]:b.value=c.map(g=>g.state_name)})},H=()=>{const i={api_id:"SP202407CRM",api_token:"vdsas3DDVv12j4vNSCJ435fgdhghGDS890cvbH1",state_name:l.value.state};G.get(`${v}/api_area_list.php`,{params:i}).then(o=>{const c=o.data;c.error?C.value=[]:C.value=c.map(g=>g.area)})},I=()=>{const i={api_id:"SP202407CRM",api_token:"vdsas3DDVv12j4vNSCJ435fgdhghGDS890cvbH1"};G.get(`${v}/api_available_products.php`,{params:i}).then(o=>{const c=o.data;c.error?y.value=[]:y.value=c.map(g=>g.product_name)})},T=()=>{const i={api_id:"SP202407CRM",api_token:"vdsas3DDVv12j4vNSCJ435fgdhghGDS890cvbH1",product_name:l.value.product};G.get(`${v}/api_available_package.php`,{params:i}).then(o=>{const c=o.data;c.error?R.value=[]:R.value=c.map(g=>g.package_name)})},B=()=>{const i=l.value;for(const c of Object.keys(i))if(c!="address3"&&c!="remark"&&(i[c]==""||!i[c])){h.value=`'${V[c]}' must not be empty!`;return}const o={api_id:"SP202407CRM",api_token:"vdsas3DDVv12j4vNSCJ435fgdhghGDS890cvbH1",product_name:i.product,package_name:i.package,customer_name:i.fullname,nric:i.nric,citizen:i.citizen,address:`${i.address1} ${i.address2} ${i.address3}`,postcode:i.postcode,state_name:i.state,area:i.area,contact:i.contact,email:i.email,agentcode:i.agentcode,gender:i.gender,race:i.race,existing_unifi:i.existingUnifi,alt_num:i.altNum,remark:i.remark};G.post(`${v}/casecrm_submitorder.php`,new URLSearchParams(o).toString(),{headers:{"Content-Type":"application/x-www-form-urlencoded"}}).then(c=>{const g=c.data;if(g instanceof Array){const u=g[0];if(u.status==0){delete u.status;const t=Object.keys(u)[0];h.value=u[t]}else u.status==1&&u.ref_number&&k("update-record",u.ref_number)}})};return le(()=>{N(),I()}),(i,o)=>{const c=Ke,g=pe,u=qe;return L(),K(ta,{modelValue:a(d),"onUpdate:modelValue":o[20]||(o[20]=t=>Y(d)?d.value=t:null),persistent:"","max-width":"720"},{default:s(()=>[e(c,{onClick:F}),e(ge,{title:"Submit to KTIC"},{default:s(()=>[e(Q,null,{default:s(()=>[e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(g,{items:a(y),modelValue:a(l).product,"onUpdate:modelValue":o[0]||(o[0]=t=>a(l).product=t),label:a(V).product},null,8,["items","modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(g,{items:a(R),modelValue:a(l).package,"onUpdate:modelValue":o[1]||(o[1]=t=>a(l).package=t),label:a(V).package},null,8,["items","modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(g,{items:a(Qe),modelValue:a(l).gender,"onUpdate:modelValue":o[2]||(o[2]=t=>a(l).gender=t),label:a(V).gender},null,8,["items","modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(g,{items:a(We),modelValue:a(l).race,"onUpdate:modelValue":o[3]||(o[3]=t=>a(l).race=t),label:a(V).race},null,8,["items","modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{items:a(y),modelValue:a(l).altNum,"onUpdate:modelValue":o[4]||(o[4]=t=>a(l).altNum=t),label:a(V).altNum},null,8,["items","modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(g,{items:a(Xe),modelValue:a(l).existingUnifi,"onUpdate:modelValue":o[5]||(o[5]=t=>a(l).existingUnifi=t),label:a(V).existingUnifi},null,8,["items","modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{modelValue:a(l).fullname,"onUpdate:modelValue":o[6]||(o[6]=t=>a(l).fullname=t),label:a(V).fullname},null,8,["modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).nric,"onUpdate:modelValue":o[7]||(o[7]=t=>a(l).nric=t),label:a(V).nric},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(g,{items:a(b),modelValue:a(l).state,"onUpdate:modelValue":o[8]||(o[8]=t=>a(l).state=t),label:a(V).state},null,8,["items","modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).address1,"onUpdate:modelValue":o[9]||(o[9]=t=>a(l).address1=t),label:a(V).address1},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(g,{items:a(C),modelValue:a(l).area,"onUpdate:modelValue":o[10]||(o[10]=t=>a(l).area=t),label:a(V).area},null,8,["items","modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).address2,"onUpdate:modelValue":o[11]||(o[11]=t=>a(l).address2=t),label:a(V).address2},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{modelValue:a(l).postcode,"onUpdate:modelValue":o[12]||(o[12]=t=>a(l).postcode=t),label:a(V).postcode},null,8,["modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).address3,"onUpdate:modelValue":o[13]||(o[13]=t=>a(l).address3=t),label:a(V).address3},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{modelValue:a(l).contact,"onUpdate:modelValue":o[14]||(o[14]=t=>a(l).contact=t),label:a(V).contact},null,8,["modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).email,"onUpdate:modelValue":o[15]||(o[15]=t=>a(l).email=t),label:a(V).email},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{modelValue:a(l).citizen,"onUpdate:modelValue":o[16]||(o[16]=t=>a(l).citizen=t),label:a(V).citizen},null,8,["modelValue","label"])]),_:1}),e(f,null,{default:s(()=>[e(u,{modelValue:a(l).agentcode,"onUpdate:modelValue":o[17]||(o[17]=t=>a(l).agentcode=t),label:a(V).agentcode},null,8,["modelValue","label"])]),_:1})]),_:1}),e(S,{class:"mx-2"},{default:s(()=>[e(f,null,{default:s(()=>[e(u,{modelValue:a(l).remark,"onUpdate:modelValue":o[18]||(o[18]=t=>a(l).remark=t),label:a(V).remark},null,8,["modelValue","label"])]),_:1})]),_:1})]),_:1}),e(Q,{class:"d-flex justify-end flex-wrap gap-3"},{default:s(()=>[e(O,{variant:"tonal",color:"secondary",onClick:F},{default:s(()=>[D(" Close ")]),_:1}),e(O,{onClick:B},{default:s(()=>[D(" Submit ")]),_:1})]),_:1}),e(te,{modelValue:a(w),"onUpdate:modelValue":o[19]||(o[19]=t=>Y(w)?w.value=t:null),timeout:1e3,location:"top end",color:"error"},{default:s(()=>[D(j(a(h)),1)]),_:1},8,["modelValue"])]),_:1})]),_:1},8,["modelValue"])}}},ra={class:"font-italic"},ia=$("b",null,"Campaign Detail ID: ",-1),da={class:"mx-2 text-primary"},ma=$("b",null,"Campaign Reference Number: ",-1),ca={class:"ml-2 text-primary"},fa={class:"font-weight-bold text-sm"},pa=$("div",{class:"font-italic font-weight-bold"}," Update Status ",-1),Aa={__name:"[id]",props:{date_filter:{type:String,required:!1}},setup(n){const k=Ee(),U=Ge(),v=parseInt(U.params.id),d=_({}),l=_(!1),b=_([]),C=_([]),y=_(!1),R=A(()=>w.value.length>0),w=_(""),h=Ze.campaignDetail.filter(u=>u.key!="id"&&u.key!="campaignAgentRemark"),F=(u,t)=>{var p,r;return t=="assigned_leader"?(p=u.leader)==null?void 0:p.name:t=="assigned_agent"?(r=u.agent)==null?void 0:r.name:u[t]||"--"},N=()=>{l.value=!0},H=()=>{l.value=!1},I=u=>u.map(t=>({value:t.id,title:t.name,parent:t.parent_id})),T=A(()=>{const u=d.value.progressStatus,t=d.value.progressSubStatus;if(u==null)return[];const p=C.value.filter(r=>r.parent==u);return t&&p.findIndex(m=>m.value==t)==-1&&(d.value.progressSubStatus=""),p}),B=()=>{k.back()},i=()=>{J.get("/admin/progress-categories").then(u=>{const{data:t}=u.data;b.value=I(t.categories),C.value=I(t.sub_categories),o()})},o=()=>{J.get(`/campaign-detail/${v}`).then(u=>{const{campaign_detail:t}=u.data,p=b.value.find(m=>m.title==t.progressStatus),r=C.value.find(m=>m.title==t.progressSubStatus);d.value={...t,progressStatus:p==null?"":p.value,progressSubStatus:r==null?"":r.value}})},c=()=>{let{progressStatus:u,progressSubStatus:t,campaignAgentRemark:p}=d.value;if(!u||u==""){w.value="Progress Status must not be empty!";return}if(!t||t==""){w.value="Progress Sub Status must not be empty!";return}u=b.value.find(r=>r.value==u).title,t=T.value.find(r=>r.value==t).title;try{J.put(`/campaign-detail/${v}/status`,{progressStatus:u,progressSubStatus:t,campaignAgentRemark:p}).then(r=>{const{status:m}=r.data;m=="success"&&(y.value=!0),k.options.history.state.back&&k.push(k.options.history.state.back)})}catch(r){console.error("Error occurred:",r)}},g=u=>{l.value=!1,d.value.ref_no=u,J.put(`/campaign-detail/${v}/ref-number`,{ref_no:u}).then(t=>{const{status:p}=t.data;p=="success"&&(y.value=!0)})};return le(()=>{i()}),(u,t)=>{const p=pe,r=na;return L(),K(ge,null,{default:s(()=>[e(aa,null,{append:s(()=>[e(O,{class:"mr-4 my-4",color:"error",onClick:B},{default:s(()=>[D(" Back to Main ")]),_:1}),e(O,{onClick:N},{default:s(()=>[D(" Submit ")]),_:1})]),default:s(()=>[e(ea,null,{default:s(()=>[D("Campaign Detail")]),_:1})]),_:1}),e(Q,{class:"pl-8"},{default:s(()=>[e(S,null,{default:s(()=>[e(f,{cols:"12"},{default:s(()=>[$("div",ra,[ia,D(),$("span",da," #"+j(a(d).id),1),ma,D(),$("span",ca," #"+j(a(d).ref_no),1)])]),_:1})]),_:1}),e(S,null,{default:s(()=>[a(d)!=null?(L(!0),me(q,{key:0},fe(a(h),(m,x)=>(L(),K(f,{cols:"4",key:x,class:"d-flex justify-between"},{default:s(()=>[$("div",fa,j(m.title)+":",1),$("div",null,j(F(a(d),m.key)),1)]),_:2},1024))),128)):ce("",!0)]),_:1}),e(la,{class:"my-6"}),e(S,null,{default:s(()=>[e(f,{cols:"12"},{default:s(()=>[pa]),_:1})]),_:1}),e(S,null,{default:s(()=>[e(f,{cols:"6"},{default:s(()=>[e(p,{modelValue:a(d).progressStatus,"onUpdate:modelValue":t[0]||(t[0]=m=>a(d).progressStatus=m),items:a(b),label:"Progress Status"},null,8,["modelValue","items"])]),_:1}),e(f,{cols:"6"},{default:s(()=>[e(p,{modelValue:a(d).progressSubStatus,"onUpdate:modelValue":t[1]||(t[1]=m=>a(d).progressSubStatus=m),items:a(T),label:"Progress Sub Status"},null,8,["modelValue","items"])]),_:1}),e(f,{cols:"12"},{default:s(()=>[e(r,{modelValue:a(d).campaignAgentRemark,"onUpdate:modelValue":t[2]||(t[2]=m=>a(d).campaignAgentRemark=m),label:"Campaign Agent Remark"},null,8,["modelValue"])]),_:1})]),_:1}),e(Q,{class:"d-flex justify-end flex-wrap gap-3"},{default:s(()=>[e(O,{onClick:c},{default:s(()=>[D(" Update ")]),_:1})]),_:1}),e(te,{modelValue:a(R),"onUpdate:modelValue":t[3]||(t[3]=m=>Y(R)?R.value=m:null),timeout:1e3,location:"top end",color:"error"},{default:s(()=>[D(j(a(w)),1)]),_:1},8,["modelValue"]),e(te,{modelValue:a(y),"onUpdate:modelValue":t[4]||(t[4]=m=>Y(y)?y.value=m:null),timeout:1e3,location:"top end",color:"success"},{default:s(()=>[D(" Status has been successfully updated. ")]),_:1},8,["modelValue"])]),_:1}),e(ua,{isDialogVisible:a(l),onCloseDialog:H,onUpdateRecord:g},null,8,["isDialogVisible"])]),_:1})}}};export{Aa as default};
