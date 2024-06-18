$(function(){
    

    // 材料を追加するボタン
    var id = 10;
    $('.add-button').on('click', function(){
        var maxCount = 10;
        var inputCount = $(this).parent().find('.unit').length;
        if (inputCount < maxCount){
            var element = $(this).parent().find('.unit:last').clone(true);
            element.find('.ingredient-id').attr('name', 'ingredients[' + id + '][id]');
            element.find('.ingredient').attr('name', 'ingredients[' + id + '][name]');//  name="ingredients[{{$i}}][name]"
            element.find('.quantity').attr('name', 'ingredients[' + id + '][quantity]');
            element.find('.process-id').attr('name', 'processes[' + id + '][id]');
            element.find('.process').attr('name', 'processes[' + id + '][name]');
            element.find('.process_image').attr('name', 'processes[' + id + '][image]');
            element.find('.imageUpload').attr('type', 'text').attr('type', 'file');
            element.find('img').attr('src', '/img/no_image.jpg');
            element.find('.clear-image').hide();
            element.find('textarea').val('');
            var inputList = element[0].querySelectorAll('input[type="text"]');
            for (var i = 0; i < inputList.length; i++) {
                inputList[i].value = "";
            }
            element.insertBefore($(this));
            id++;
            $(this).parent().find('.unit:last').find('.remove-button').show();
            if(inputCount == maxCount - 1){
                $(this).hide();
            }
        }
    })

    $('.remove-button').on('click', function(){
        var inputCount = $(this).parent().find('.unit').length;
        $(this).parents('.unit').remove();
        if(inputCount <= 10){
            $('.add-button').show();
        }
    })


    //点数スライダーの現在値を取得
    $('#current-value').html($('#score').val());
    $('#score').on('input change', function() {
        // 変動
        $('#current-value').html($(this).val());
    })


    //レシピ削除時のアラート
    $('#delete-btn').click(function(){
        var title = $(this).data('item-title');

        if(!confirm("「" + title + "」を削除してよろしいですか？")){
            /* キャンセルの時の処理 */
            return false;
        }else{
            /* OKの時の処理 */            
        }
    })
    

    //画像のプレビュー表示
    $(document).on("change", ".imageUpload", function(){
        let elem = this                                             //操作された要素を取得
        let fileReader = new FileReader();                          //ファイルを読み取るオブジェクトを生成
        fileReader.readAsDataURL(elem.files[0]);                    //ファイルを読み取る
        fileReader.onload = (function () {                          //ファイル読み取りが完了したら
            $(elem).next(".preview").attr('src', `${fileReader.result}`)     //画像をプレビュー
            $(elem).parent().find('.scraped_image').val('');        
            // クリアするボタンを表示
            $(elem).parent().parent().find('.clear-image').show();
        });
    })

    // ファイル参照をクリア
    $('.clear-image').click(function() {
        $(this).parent().find('.imageUpload').attr('type', 'text').attr('type', 'file');
        $(this).parent().find('img').attr('src', '/img/no_image.jpg');
        $(this).parent().find('.scraped_image').val('');
        $(this).hide();
    });

    //スクレイピング時の画像の処理
    if($('.scraped_image').length){
        $('.scraped_image').each(function() {
            let image_text = $(this).parent().find('.preview').attr('src');
            if(image_text !== ' http://127.0.0.1:8000/img/no_image.jpg '){
                $(this).val(image_text);
            }
        });
    }

    // アイコンクリアするボタンを表示
    $('.icon-input').change(function() {
        $(this).parent().parent().parent().find('.clear-icon').show();
    });
    // アイコン選択をクリア
    $('.clear-icon').click(function() {
        $(this).parent().find('.icon-input').val('');
        $(this).parent().find('.view_icon').html('');
        $(this).hide();
    })


    // スクレイピングするサイトを選択
    $('[name="scrape"]:radio').change( function() {
        // クックパッドの場合
        if($('[id=cookpad]').prop('checked')){
            $('#scrape_link').attr('formaction', '/items/add/scrape/cookpad');
            $('#scrape_input').attr('placeholder', 'クックパッドのURL');

        // 楽天レシピの場合
        }else if($('[id=rakuten]').prop('checked')){
            $('#scrape_link').attr('formaction', '/items/add/scrape/rakuten');
            $('#scrape_input').attr('placeholder', '楽天レシピのURL');
        }

        $('#scrape_input').prop('hidden', false);
        $('#scrape_link').prop('hidden', false);
    })

    // ヘッダーのプルダウンメニュー
    $("ul.menu li").hover(
        function() {
            $(".menuSub:not(:animated)", this).slideDown();
        },
        function() {
            $(".menuSub", this).slideUp();
        }
    );

    //問い合わせ送信時のアラート
    $('.info-btn').click(function(){
        var title = $(this).parent().find('input:text[name="title"]').val();
        var content = $(this).parent().find('textarea').val();
        console.log(title);

        if(!confirm("タイトル：" + title + "\n内容：" + content + "\nを送信してよろしいですか？")){
            /* キャンセルの時の処理 */
            return false;
        }else{
            /* OKの時の処理 */            
        }
    })

    // お知らせ未読から既読に変更
    $('.change-status').click(function(){
        const notice_id = $(this).data('notice-id');
        const status = $(this).data('status');
        cache: false
        if(status == 'unread'){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/notice',
                type: 'PATCH',
                data: {
                    'notice_id': notice_id
                },
                timeout: 10000
            })
                //成功
                .done(() => {
                    // !アイコン削除
                    $(document).find('.exclamation-' + notice_id).hide();
                })
                //失敗
                .fail((data) => {
                    alert('処理中にエラーが発生しました。');
                    console.log(data);
                });
        }
    })

});
