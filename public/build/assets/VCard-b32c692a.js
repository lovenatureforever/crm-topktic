import{N as b,$ as y,aq as $,S as f,n,av as u,aE as h,aQ as k,ar as q,aS as w,aF as z,ax as J,b6 as K,aG as O,aH as Q,az as j,bi as U,a0 as W,an as X,aI as Y,ap as Z,aV as ee,aJ as ae,aK as te,aL as ne,aB as de,b7 as ie,aM as se,aN as le,aC as ce,bk as re,G as p,I as ue,ae as ve,b8 as oe,aP as me}from"./main-b50d5463.js";import{c as C,V as A}from"./VAvatar-8d1777e9.js";import{V as ge}from"./VImg-baea078e.js";const ke=b()({name:"VCardActions",props:y(),setup(e,i){let{slots:t}=i;return $({VBtn:{variant:"text"}}),f(()=>{var a;return n("div",{class:["v-card-actions",e.class],style:e.style},[(a=t.default)==null?void 0:a.call(t)])}),{}}}),be=C("v-card-subtitle"),ye=C("v-card-title"),fe=b()({name:"VCardItem",props:{appendAvatar:String,appendIcon:u,prependAvatar:String,prependIcon:u,subtitle:String,title:String,...y(),...h()},setup(e,i){let{slots:t}=i;return f(()=>{var l;const a=!!(e.prependAvatar||e.prependIcon),v=!!(a||t.prepend),s=!!(e.appendAvatar||e.appendIcon),o=!!(s||t.append),m=!!(e.title||t.title),g=!!(e.subtitle||t.subtitle);return n("div",{class:["v-card-item",e.class],style:e.style},[v&&n("div",{key:"prepend",class:"v-card-item__prepend"},[t.prepend?n(k,{key:"prepend-defaults",disabled:!a,defaults:{VAvatar:{density:e.density,icon:e.prependIcon,image:e.prependAvatar}}},t.prepend):a&&n(A,{key:"prepend-avatar",density:e.density,icon:e.prependIcon,image:e.prependAvatar},null)]),n("div",{class:"v-card-item__content"},[m&&n(ye,{key:"title"},{default:()=>{var d;return[((d=t.title)==null?void 0:d.call(t))??e.title]}}),g&&n(be,{key:"subtitle"},{default:()=>{var d;return[((d=t.subtitle)==null?void 0:d.call(t))??e.subtitle]}}),(l=t.default)==null?void 0:l.call(t)]),o&&n("div",{key:"append",class:"v-card-item__append"},[t.append?n(k,{key:"append-defaults",disabled:!s,defaults:{VAvatar:{density:e.density,icon:e.appendIcon,image:e.appendAvatar}}},t.append):s&&n(A,{key:"append-avatar",density:e.density,icon:e.appendIcon,image:e.appendAvatar},null)])])}),{}}}),Ce=C("v-card-text"),Ae=b()({name:"VCard",directives:{Ripple:q},props:{appendAvatar:String,appendIcon:u,disabled:Boolean,flat:Boolean,hover:Boolean,image:String,link:{type:Boolean,default:void 0},prependAvatar:String,prependIcon:u,ripple:{type:Boolean,default:!0},subtitle:String,text:String,title:String,...w(),...y(),...h(),...z(),...J(),...K(),...O(),...Q(),...j(),...U(),...W(),...X(),...Y({variant:"elevated"})},setup(e,i){let{attrs:t,slots:a}=i;const{themeClasses:v}=Z(e),{borderClasses:s}=ee(e),{colorClasses:o,colorStyles:m,variantClasses:g}=ae(e),{densityClasses:l}=te(e),{dimensionStyles:d}=ne(e),{elevationClasses:P}=de(e),{loaderClasses:S}=ie(e),{locationStyles:x}=se(e),{positionClasses:B}=le(e),{roundedClasses:L}=ce(e),c=re(e,t),T=p(()=>e.link!==!1&&c.isLink.value),r=p(()=>!e.disabled&&e.link!==!1&&(e.link||c.isClickable.value));return f(()=>{const D=T.value?"a":e.tag,_=!!(a.title||e.title),R=!!(a.subtitle||e.subtitle),E=_||R,M=!!(a.append||e.appendAvatar||e.appendIcon),N=!!(a.prepend||e.prependAvatar||e.prependIcon),F=!!(a.image||e.image),G=E||N||M,H=!!(a.text||e.text);return ue(n(D,{class:["v-card",{"v-card--disabled":e.disabled,"v-card--flat":e.flat,"v-card--hover":e.hover&&!(e.disabled||e.flat),"v-card--link":r.value},v.value,s.value,o.value,l.value,P.value,S.value,B.value,L.value,g.value,e.class],style:[m.value,d.value,x.value,e.style],href:c.href.value,onClick:r.value&&c.navigate,tabindex:e.disabled?-1:void 0},{default:()=>{var I;return[F&&n("div",{key:"image",class:"v-card__image"},[a.image?n(k,{key:"image-defaults",disabled:!e.image,defaults:{VImg:{cover:!0,src:e.image}}},a.image):n(ge,{key:"image-img",cover:!0,src:e.image},null)]),n(oe,{name:"v-card",active:!!e.loading,color:typeof e.loading=="boolean"?void 0:e.loading},{default:a.loader}),G&&n(fe,{key:"item",prependAvatar:e.prependAvatar,prependIcon:e.prependIcon,title:e.title,subtitle:e.subtitle,appendAvatar:e.appendAvatar,appendIcon:e.appendIcon},{default:a.item,prepend:a.prepend,title:a.title,subtitle:a.subtitle,append:a.append}),H&&n(Ce,{key:"text"},{default:()=>{var V;return[((V=a.text)==null?void 0:V.call(a))??e.text]}}),(I=a.default)==null?void 0:I.call(a),a.actions&&n(ke,null,{default:a.actions}),me(r.value,"v-card")]}}),[[ve("ripple"),r.value&&e.ripple]])}),{}}});export{Ae as V,fe as a,ye as b,ke as c,Ce as d};
