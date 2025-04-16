import{N as y,$ as b,an as H,S as f,n,aw as u,aE as h,aQ as k,as as $,aS as J,aF as K,ay as O,b6 as Q,aG as j,aH as q,aA as z,bi as U,a0 as W,ap as X,aI as Y,ar as Z,aV as ee,aJ as ae,aK as te,aL as ne,aC as de,b7 as ie,aM as se,aN as le,aD as ce,bk as re,G as V,I as ue,ae as ve,b8 as oe,aP as me}from"./main-e2e587ad.js";import{c as C,V as p}from"./VAvatar-59d5fe07.js";import{V as ge}from"./VImg-8c8e46ee.js";const ke=y()({name:"VCardActions",props:b(),setup(e,i){let{slots:t}=i;return H({VBtn:{variant:"text"}}),f(()=>{var a;return n("div",{class:["v-card-actions",e.class],style:e.style},[(a=t.default)==null?void 0:a.call(t)])}),{}}}),ye=C("v-card-subtitle"),be=C("v-card-title"),fe=y()({name:"VCardItem",props:{appendAvatar:String,appendIcon:u,prependAvatar:String,prependIcon:u,subtitle:String,title:String,...b(),...h()},setup(e,i){let{slots:t}=i;return f(()=>{var l;const a=!!(e.prependAvatar||e.prependIcon),v=!!(a||t.prepend),s=!!(e.appendAvatar||e.appendIcon),o=!!(s||t.append),m=!!(e.title||t.title),g=!!(e.subtitle||t.subtitle);return n("div",{class:["v-card-item",e.class],style:e.style},[v&&n("div",{key:"prepend",class:"v-card-item__prepend"},[t.prepend?n(k,{key:"prepend-defaults",disabled:!a,defaults:{VAvatar:{density:e.density,icon:e.prependIcon,image:e.prependAvatar}}},t.prepend):a&&n(p,{key:"prepend-avatar",density:e.density,icon:e.prependIcon,image:e.prependAvatar},null)]),n("div",{class:"v-card-item__content"},[m&&n(be,{key:"title"},{default:()=>{var d;return[((d=t.title)==null?void 0:d.call(t))??e.title]}}),g&&n(ye,{key:"subtitle"},{default:()=>{var d;return[((d=t.subtitle)==null?void 0:d.call(t))??e.subtitle]}}),(l=t.default)==null?void 0:l.call(t)]),o&&n("div",{key:"append",class:"v-card-item__append"},[t.append?n(k,{key:"append-defaults",disabled:!s,defaults:{VAvatar:{density:e.density,icon:e.appendIcon,image:e.appendAvatar}}},t.append):s&&n(p,{key:"append-avatar",density:e.density,icon:e.appendIcon,image:e.appendAvatar},null)])])}),{}}}),Ce=C("v-card-text"),pe=y()({name:"VCard",directives:{Ripple:$},props:{appendAvatar:String,appendIcon:u,disabled:Boolean,flat:Boolean,hover:Boolean,image:String,link:{type:Boolean,default:void 0},prependAvatar:String,prependIcon:u,ripple:{type:Boolean,default:!0},subtitle:String,text:String,title:String,...J(),...b(),...h(),...K(),...O(),...Q(),...j(),...q(),...z(),...U(),...W(),...X(),...Y({variant:"elevated"})},setup(e,i){let{attrs:t,slots:a}=i;const{themeClasses:v}=Z(e),{borderClasses:s}=ee(e),{colorClasses:o,colorStyles:m,variantClasses:g}=ae(e),{densityClasses:l}=te(e),{dimensionStyles:d}=ne(e),{elevationClasses:P}=de(e),{loaderClasses:S}=ie(e),{locationStyles:x}=se(e),{positionClasses:D}=le(e),{roundedClasses:L}=ce(e),c=re(e,t),T=V(()=>e.link!==!1&&c.isLink.value),r=V(()=>!e.disabled&&e.link!==!1&&(e.link||c.isClickable.value));return f(()=>{const B=T.value?"a":e.tag,_=!!(a.title||e.title),R=!!(a.subtitle||e.subtitle),E=_||R,M=!!(a.append||e.appendAvatar||e.appendIcon),N=!!(a.prepend||e.prependAvatar||e.prependIcon),w=!!(a.image||e.image),F=E||N||M,G=!!(a.text||e.text);return ue(n(B,{class:["v-card",{"v-card--disabled":e.disabled,"v-card--flat":e.flat,"v-card--hover":e.hover&&!(e.disabled||e.flat),"v-card--link":r.value},v.value,s.value,o.value,l.value,P.value,S.value,D.value,L.value,g.value,e.class],style:[m.value,d.value,x.value,e.style],href:c.href.value,onClick:r.value&&c.navigate,tabindex:e.disabled?-1:void 0},{default:()=>{var A;return[w&&n("div",{key:"image",class:"v-card__image"},[a.image?n(k,{key:"image-defaults",disabled:!e.image,defaults:{VImg:{cover:!0,src:e.image}}},a.image):n(ge,{key:"image-img",cover:!0,src:e.image},null)]),n(oe,{name:"v-card",active:!!e.loading,color:typeof e.loading=="boolean"?void 0:e.loading},{default:a.loader}),F&&n(fe,{key:"item",prependAvatar:e.prependAvatar,prependIcon:e.prependIcon,title:e.title,subtitle:e.subtitle,appendAvatar:e.appendAvatar,appendIcon:e.appendIcon},{default:a.item,prepend:a.prepend,title:a.title,subtitle:a.subtitle,append:a.append}),G&&n(Ce,{key:"text"},{default:()=>{var I;return[((I=a.text)==null?void 0:I.call(a))??e.text]}}),(A=a.default)==null?void 0:A.call(a),a.actions&&n(ke,null,{default:a.actions}),me(r.value,"v-card")]}}),[[ve("ripple"),r.value&&e.ripple]])}),{}}});export{pe as V,fe as a,be as b,ke as c,Ce as d};
