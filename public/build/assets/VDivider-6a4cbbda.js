import{N as $,n as v,H as b,T as y,$ as P,ap as V,ar as B,aO as D,a1 as k,G as A,ag as p,S as q}from"./main-e2e587ad.js";import{d as f,e as F,s as x,g as N,n as O}from"./forwardRefs-b6befefd.js";const H=$()({name:"VDialogTransition",props:{target:Object},setup(e,s){let{slots:n}=s;const o={onBeforeEnter(t){t.style.pointerEvents="none",t.style.visibility="hidden"},async onEnter(t,d){var c;await new Promise(a=>requestAnimationFrame(a)),await new Promise(a=>requestAnimationFrame(a)),t.style.visibility="";const{x:r,y:i,sx:h,sy:m,speed:l}=C(e.target,t),g=f(t,[{transform:`translate(${r}px, ${i}px) scale(${h}, ${m})`,opacity:0},{}],{duration:225*l,easing:F});(c=w(t))==null||c.forEach(a=>{f(a,[{opacity:0},{opacity:0,offset:.33},{}],{duration:225*2*l,easing:x})}),g.finished.then(()=>d())},onAfterEnter(t){t.style.removeProperty("pointer-events")},onBeforeLeave(t){t.style.pointerEvents="none"},async onLeave(t,d){var c;await new Promise(a=>requestAnimationFrame(a));const{x:r,y:i,sx:h,sy:m,speed:l}=C(e.target,t);f(t,[{},{transform:`translate(${r}px, ${i}px) scale(${h}, ${m})`,opacity:0}],{duration:125*l,easing:N}).finished.then(()=>d()),(c=w(t))==null||c.forEach(a=>{f(a,[{},{opacity:0,offset:.2},{opacity:0}],{duration:125*2*l,easing:x})})},onAfterLeave(t){t.style.removeProperty("pointer-events")}};return()=>e.target?v(y,b({name:"dialog-transition"},o,{css:!1}),n):v(y,{name:"dialog-transition"},n)}});function w(e){var n;const s=(n=e.querySelector(":scope > .v-card, :scope > .v-sheet, :scope > .v-list"))==null?void 0:n.children;return s&&[...s]}function C(e,s){const n=e.getBoundingClientRect(),o=O(s),[t,d]=getComputedStyle(s).transformOrigin.split(" ").map(T=>parseFloat(T)),[r,i]=getComputedStyle(s).getPropertyValue("--v-overlay-anchor-origin").split(" ");let h=n.left+n.width/2;r==="left"||i==="left"?h-=n.width/2:(r==="right"||i==="right")&&(h+=n.width/2);let m=n.top+n.height/2;r==="top"||i==="top"?m-=n.height/2:(r==="bottom"||i==="bottom")&&(m+=n.height/2);const l=n.width/o.width,g=n.height/o.height,c=Math.max(1,l,g),a=l/c||0,E=g/c||0,u=o.width*o.height/(window.innerWidth*window.innerHeight),S=u>.12?Math.min(1.5,(u-.12)*10+1):1;return{x:h-(t+o.left),y:m-(d+o.top),sx:a,sy:E,speed:S}}const L=$()({name:"VDivider",props:{color:String,inset:Boolean,length:[Number,String],thickness:[Number,String],vertical:Boolean,...P(),...V()},setup(e,s){let{attrs:n}=s;const{themeClasses:o}=B(e),{textColorClasses:t,textColorStyles:d}=D(k(e,"color")),r=A(()=>{const i={};return e.length&&(i[e.vertical?"maxHeight":"maxWidth"]=p(e.length)),e.thickness&&(i[e.vertical?"borderRightWidth":"borderTopWidth"]=p(e.thickness)),i});return q(()=>v("hr",{class:[{"v-divider":!0,"v-divider--inset":e.inset,"v-divider--vertical":e.vertical},o.value,t.value,e.class],style:[r.value,d.value,e.style],"aria-orientation":!n.role||n.role==="separator"?e.vertical?"vertical":"horizontal":void 0,role:`${n.role||"separator"}`},null)),{}}});export{L as V,H as a};
