<div class="side">
    <div class="side-inner">
        <form action="/search" method="post">
            <div class="form-group">
                <input type="text" name="keyword" placeholder="フリーワード">
            </div>

            <div class="form-group">
                <label>ジャンル</label>
                <div>
                    <input type="checkbox" name="genre" value="jap" id="jap" class="genre"><label for="jap" class="genre checkbox-label">和</label>
                    <input type="checkbox" name="genre" value="wes" id="wes" class="genre"><label for="wes" class="genre checkbox-label">洋</label>
                    <input type="checkbox" name="genre" value="chi" id="chi" class="genre"><label for="chi" class="genre checkbox-label">中</label>
                </div>
            </div>

            <div class="form-group">
                <label>カテゴリ</label>
                <div>
                    <input type="checkbox" name="category" value="main" id="main" class="category"><label for="main" class="category checkbox-label">主<span class="tooltip">主菜</span></label>
                    <input type="checkbox" name="category" value="side" id="side" class="category"><label for="side" class="category checkbox-label">副<span class="tooltip">副菜</span></label>
                    <input type="checkbox" name="category" value="soup" id="soup" class="category"><label for="soup" class="category checkbox-label">汁<span class="tooltip">汁物</span></label>
                    <input type="checkbox" name="category" value="sweets" id="sweets" class="category"><label for="sweets" class="category checkbox-label">菓<span class="tooltip">お菓子</span></label>
                </div>
            </div>
            
            <div class="form-group">
                <label>タグ</label>
                <div>
                    @if(count($tags) == 0)
                        <a href="{{url('items/editTag')}}">タグを追加する</a>
                    @else
                        @foreach($tags as $tag)
                            <input type="checkbox" name="tag" value="tag{{$tag->number}}" id="tag{{$tag->number}}" class="tag"><label for="tag{{$tag->number}}" class="tag checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></label>
                        @endforeach
                    @endif
                </div>
            </div>

            <input type="submit" value="検索">
        </form>
    </div>
</div>