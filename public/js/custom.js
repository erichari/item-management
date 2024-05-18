$(function(){

    // //並び替えフォーム自動送信
    // $('#sort-item').change(function(){
    //     $("#sort-form").submit();
    // })

    
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
            element.find('label').attr('for', 'imageUpload' + id);
            element.find('.imageUpload').attr('type', 'text').attr('type', 'file');
            element.find('.imageUpload').attr('id','imageUpload' + id);
            element.find('img').attr('src', '/img/no_image.jpg');
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
        var id = $(this).data('item-id');

        if(!confirm("「" + title + "」を削除してよろしいですか？")){
            /* キャンセルの時の処理 */
            return false;
        }else{
            /*　OKの時の処理 */            
        }
    })
    

    //画像のプレビュー表示
    $(document).on("change",".imageUpload",function(){
        let elem = this                                             //操作された要素を取得
        let fileReader = new FileReader();                          //ファイルを読み取るオブジェクトを生成
        fileReader.readAsDataURL(elem.files[0]);                    //ファイルを読み取る
        fileReader.onload = (function () {                          //ファイル読み取りが完了したら
            $(elem).next(".preview").attr('src', `${fileReader.result}`)             //画像をプレビュー
        });
    })

});
