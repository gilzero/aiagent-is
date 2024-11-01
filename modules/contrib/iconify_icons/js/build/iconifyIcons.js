!function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports.CKEditor5=t():(e.CKEditor5=e.CKEditor5||{},e.CKEditor5.iconifyIcons=t())}(self,(()=>(()=>{var e={"ckeditor5/src/core.js":(e,t,o)=>{e.exports=o("dll-reference CKEditor5.dll")("./src/core.js")},"ckeditor5/src/ui.js":(e,t,o)=>{e.exports=o("dll-reference CKEditor5.dll")("./src/ui.js")},"ckeditor5/src/widget.js":(e,t,o)=>{e.exports=o("dll-reference CKEditor5.dll")("./src/widget.js")},"dll-reference CKEditor5.dll":e=>{"use strict";e.exports=CKEditor5.dll}},t={};function o(i){var r=t[i];if(void 0!==r)return r.exports;var s=t[i]={exports:{}};return e[i](s,s.exports,o),s.exports}o.d=(e,t)=>{for(var i in t)o.o(t,i)&&!o.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t);var i={};return(()=>{"use strict";o.d(i,{default:()=>d});var e=o("ckeditor5/src/core.js"),t=o("ckeditor5/src/widget.js");class r extends e.Command{execute(e){this.editor.model.change((t=>{const o={src:e.icon_src,alt:e.icon_alt,class:"iconify-icons-ckeditor"},i=t.createElement("iconifyIconImg",o),r=t.createDocumentFragment();t.append(i,r),this.editor.model.insertContent(r)}))}refresh(){const{model:e}=this.editor,{selection:t}=e.document,o=e.schema.findAllowedParent(t.getFirstPosition(),"iconifyIconImg");this.isEnabled=null!==o}}class s extends e.Plugin{static get requires(){return[t.Widget]}init(){this._defineSchema(),this._defineConverters(),this._defineCommands()}_defineSchema(){const{schema:e}=this.editor.model;e.register("iconifyIconImg",{isObject:!0,isInline:!0,allowWhere:"$text",allowAttributes:["class","src","alt"]})}_defineConverters(){const{conversion:e}=this.editor;e.attributeToAttribute({model:"class",view:"class"}),e.attributeToAttribute({model:"src",view:"src"}),e.attributeToAttribute({model:"alt",view:"alt"}),e.for("downcast").elementToElement({model:"iconifyIconImg",view:(e,{writer:t})=>{const o=e.getAttribute("class"),i=e.getAttribute("src"),r=e.getAttribute("alt");return t.createEmptyElement("img",{class:o,src:i,alt:r})}}),e.for("upcast").elementToElement({view:{name:"img",classes:"iconify-icons-ckeditor"},model:(e,{writer:t})=>{const o=e.getAttribute("class"),i=e.getAttribute("src"),r=e.getAttribute("alt");return t.createElement("iconifyIconImg",{class:o,src:i,alt:r})}})}_defineCommands(){this.editor.commands.add("insertIconifyIcons",new r(this.editor))}}var c=o("ckeditor5/src/ui.js");class n extends e.Plugin{init(){const{editor:e}=this,t=this.editor.config.get("iconifyIcons");if(!t)return;const{openDialog:o,dialogSettings:i,collections:r={}}=t;"function"==typeof o&&(i.collections=r,e.ui.componentFactory.add("iconifyIcons",(t=>{const r=new c.ButtonView(t);return r.set({label:Drupal.t("Iconify Icons"),icon:'<?xml version="1.0" encoding="utf-8"?>\x3c!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --\x3e\n<svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M12 19.5c3.75 0 7.159-3.379 6.768-4.125-.393-.75-2.268 1.875-6.768 1.875s-6-2.625-6.375-1.875S8.25 19.5 12 19.5zm4.125-12c.623 0 1.125.502 1.125 1.125v1.5c0 .623-.502 1.125-1.125 1.125A1.123 1.123 0 0115 10.125v-1.5c0-.623.502-1.125 1.125-1.125zm-8.25 0C8.498 7.5 9 8.002 9 8.625v1.5c0 .623-.502 1.125-1.125 1.125a1.123 1.123 0 01-1.125-1.125v-1.5c0-.623.502-1.125 1.125-1.125zM12 0C5.381 0 0 5.381 0 12s5.381 12 12 12 12-5.381 12-12S18.619 0 12 0zm0 1.5c5.808 0 10.5 4.692 10.5 10.5S17.808 22.5 12 22.5 1.5 17.808 1.5 12 6.192 1.5 12 1.5Z"/></svg>',tooltip:!0}),this.listenTo(r,"execute",(()=>{o(Drupal.url("iconify_icons/dialog"),(({settings:t})=>{e.execute("insertIconifyIcons",t)}),i)})),r})))}}class l extends e.Plugin{static get requires(){return[s,n]}static get pluginName(){return"IconifyIcons"}}const d={IconifyIcons:l}})(),i=i.default})()));