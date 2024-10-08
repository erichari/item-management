//アイコンピッカー
$(function(){
    //表示したいアイコン
    const icons = ['fa-thumbs-up', 'fa-thumbs-down', 'fa-heart', 'fa-heart-broken', 'fa-face-laugh', 'fa-face-laugh-beam', 'fa-dizzy', 'fa-face-tired', 'fa-face-grin-hearts', 'fa-bowl-food', 'fa-bread-slice', 'fa-mug-hot', 'fa-fish', 'fa-pepper-hot', 'fa-martini-glass', 'fa-ice-cream', 'fa-egg', 'fa-seedling', 'fa-shrimp', 'fa-pizza-slice', 'fa-cookie', 'fa-cheese', 'fa-carrot', 'fa-cake-candles', 'fa-candy-cane', 'fa-burger', 'fa-bone', 'fa-blender', 'fa-apple-whole', 'fa-clock-four', 'fa-car-side', 'fa-cannabis', 'fa-snowflake', 'fa-capsules', 'fa-campground', 'fa-baby-carriage', 'fa-fire', 'fa-gift', 'fa-hourglass-2', 'fa-moon', 'fa-music', 'fa-paperclip', 'fa-paw', 'fa-poo', 'fa-star', 'fa-users', 'fa-cutlery', 'fa-weight-scale', 'fa-tablets', 'fa-crown', 'fa-bolt', 'fa-beer-mug-empty'];
    
    //クリックでポップオーバー表示
    $('.icon-btn').popover({placement:'right',
        html:true,
        content: function(){
            
            let drawDiv = $("<div>", {
                class: 'drawDiv',
            });
            var btn_id = $(this).data('btn-id');
            $.each(icons, function(index, value) {
                drawDiv.append(`
                    <button class="btn btn-outline-info mb-1 me-1 mr-1" style="width: 3em; height: 3em;" data-bs-name="${value}" onclick="addEventListener('click', iconToInput(${btn_id}))">
                        <i class='fa-solid ${value}'></i>
                    </button>
                `);
            });
            return drawDiv;
        },
        trigger: 'focus',

    })
    





});

// class Fa6IconPicker {
//     title = 'アイコンを選択'; // title popoverに渡されるタイトル
//     targetInput = document.querySelector('input[type=search]'); // 結果を返すinput要素
//     targetViewIcon = document.getElementById('view_icon'); // アイコンのプレビューを渡す要素
//     popoverElement = null; // popoverする要素
//     targetCssName = "all.min.css"; // 解析するCSS名
//     enableIcons = []; // ホワイトリスト
//     disableIcons = []; // ブラックリスト
//     isTargetInputClickEvent = true; // 結果を返すinputにイベントを与えるかどうか
//     showBtnElement = document.getElementById('select_btn'); // 結果を返すinputのほかにクリックしてpopoverを表示させる要素
//     popoverOptions = { // popoverのオプション bootstrapに依存
//         placement: 'bottom',
//     };
//     maxWidth = '320px'; // popoverの最大幅
//     isDispose = true; // 破壊されているかどうか
//     extProps = {}; // 任意に追加された変数

//     // 以下内部変数
//     #targetSheet = null;
//     #containerDiv = null;
//     #drawDiv = null;
//     constructor(settings) {
//         // 必要な依存関係が解決できるか確認
//         if(bootstrap === void 0) throw new Error('fontawesome6-icon-picker error: 依存関係を解決できません bootstrapがロードされていません');
//         if(bootstrap.Popover === void 0) throw new Error('fontawesome6-icon-picker error: 依存関係を解決できません bootstrapにPopoverが含まれていません Popoverバンドル版を使用してください');
//         if(!bootstrap.Popover.VERSION.startsWith('5.')) console.log('fontawesome6-icon-picker info: 依存関係情報 bootstrapのバージョンは5.x.xを使用してください');
//         this.#containerDiv = document.createElement('div');
//         this.#drawDiv = document.createElement('div');
//         this.#drawDiv.style.height = "600px";
//         this.#drawDiv.style.overflowY = 'scroll';
//         this.#containerDiv.appendChild(this.#drawDiv);
//         if(settings === void 0) return; // 設定がなければコンストラクタを終了
//         // 設定を適用する
//         for(const setting_name of Object.keys(settings)){
//             this[setting_name] === void 0 ? this.extProps[setting_name] = settings[setting_name] : this[setting_name] = settings[setting_name];
//         }
//         if(this.targetInput !== null) this.create(); // 表示ターゲットが決まっていればpickerの生成を試みる
//     }
//     create(){
//         this.chackFaCss();
//         if(this.targetInput === null) throw new Error('fontawesome6-icon-picker error: 値を返すinputをtargetInputに指定してください');
//         if(this.isDispose === false) throw new Error('fontawesome6-icon-picker error: すでに開始されています 再生成した場合は、一度disposeしてください');
//         this.initPopover();
//         this.isDispose = false;
//     }
//     initPopover(){
//         if(!this.popoverElement) this.popoverElement = this.targetInput;
//         this.popover = new bootstrap.Popover(this.popoverElement, {...{
//             title: this.title ?? "",
//             content: this.#containerDiv,
//             html: true,
//             trigger: 'manual',
//         },...this.popoverOptions});
//         this.popover.getTipElement().style.maxWidth = this.maxWidth; 
//         this.toggleHandler = this.toggle.bind(this);
//         if(this.isTargetInputClickEvent){
//             this.targetInput.addEventListener('click', this.toggleHandler);
//         };
//         if(this.showBtnElement){
//             this.showBtnElement.addEventListener('click', this.toggleHandler);
//         };
//     }
//     // 指定されたスタイルシートが認識できる状態にあるか確認し、存在する場合はそのスタイルシートを返す
//     chackFaCss(){
//         // 指定されたスタイルシートを選択する
//         const styleSheets = Array.from(document.styleSheets).filter((styleSheet) => styleSheet.href !== null && styleSheet.href.endsWith(this.targetCssName));
//         if(styleSheets === void 0 || styleSheets.length == 0){
//             throw new Error('fontawesome6-icon-picker error: 指定されたスタイルシートが見つかりません');
//         }
//         // オブジェクトがCSSStyleSheetであることを確認 & CORS非対応のため取り除く
//         const targetSheets = styleSheets.filter((styleSheet) => (!styleSheet.href || styleSheet.href.startsWith(window.location.origin)) && styleSheet instanceof CSSStyleSheet && styleSheet.cssRules);
//         if(targetSheets.length < 1){
//             this.loadCSSCors(styleSheets[0].href);
//         }else{
//             this.#targetSheet = targetSheets[0];
//             this.drawIcons();
//         }
//     }
//     // CSSを解析してアイコンリストを描画します
//     loadCSSCors(stylesheet_uri) {
//         console.log(stylesheet_uri);
//         var _xhr = globalThis.XMLHttpRequest;
//         var has_cred = false;
//         try {has_cred = _xhr && ('withCredentials' in (new _xhr()));} catch(e) {}
//         if (!has_cred) {
//             console.error('CORS not supported');
//             return;
//         }
//         var xhr = new _xhr();
//         xhr.open('GET', stylesheet_uri);
//         xhr.onload = () => {
//             xhr.onload = xhr.onerror = null;
//             if (xhr.status < 200 || xhr.status >= 300) {
//                 console.error('style failed to load: ' + stylesheet_uri);
//             } else {
//                 var style_tag = document.createElement('style');
//                 style_tag.appendChild(document.createTextNode(xhr.responseText));
//                 document.head.appendChild(style_tag);
//                 const targetSheet = document.styleSheets[document.styleSheets.length-1];
//                 targetSheet.disabled = true;
//                 this.#targetSheet = targetSheet;
//                 this.drawIcons();
//             }
//         };
//         xhr.onerror = function() {
//             xhr.onload = xhr.onerror = null;
//             console.error('XHR CORS CSS fail:' + styleURI);
//         };
//         xhr.send();
//     }
//     drawIcons(){
//         this.#drawDiv.innerHTML = "";
//         const members = Array.from(this.#targetSheet.cssRules).filter(v => v instanceof CSSStyleRule && v.style.content !== "");
//         let zFlag = false;
//         members.forEach(function(v,k) {
//             if(members[k-1] !== void 0 && members[k-1].style.content == v.style.content) return;
//             const iconName = v.selectorText.replace("::before", "").replace(".","");
//             let i = document.createElement("i");
//             i.classList.add(zFlag ? 'fa-brands' : 'fa-solid',iconName.split(', .')[0]);
//             const btn = document.createElement('button');
//             btn.classList.add('btn','btn-outline-secondary','mb-1','me-1','mr-1');
//             btn.setAttribute('data-fapc-icon-name',iconName.split(', .')[0]);
//             btn.setAttribute('data-fapc-icon-type',zFlag ? 'fa-brands' : 'fa-solid');
//             btn.style.width = "3em";
//             btn.style.height = "3em";
//             btn.addEventListener('click',(event) => {
//                 const name = event.currentTarget.getAttribute('data-fapc-icon-name');
//                 const type = event.currentTarget.getAttribute('data-fapc-icon-type');
//                 this.targetInput.value = type + ' ' + name;
//                 if(this.targetViewIcon){
//                     this.targetViewIcon.setAttribute('class', '');
//                     this.targetViewIcon.classList.add(name,type);
//                 }
//                 this.hide();
//             });
//             this.#drawDiv.appendChild(btn).appendChild(i);
//             if(iconName == "fa-z"){
//                 zFlag = true;
//             }
//         },this);
//     }

//     // pickerShow
//     show(){
//         this.popover.show();
//     }
//     // pickerHide
//     hide(){
//         this.popover.hide();
//     }
//     // pickerToggle
//     toggle(){
//         this.popover.toggle();
//     }
//     // dispose break
//     dispose(){
//         this.popover.dispose();
//         this.popover = null;
//         this.#containerDiv.remove();
//         this.#containerDiv = null;
//         this.targetInput.removeEventListener('click', this.toggleHandler);
//         this.showBtnElement.removeEventListener('click', this.toggleHandler);
//         this.isDispose = true;
//     }
// }
