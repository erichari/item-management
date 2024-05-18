<div class="side">
    <div class="side-inner">
        <form action="/items/search" method="get">
            @csrf
            <div class="form-group">
                <input type="text" name="keyword" placeholder="フリーワード" value="@if(isset($search_parameters['keyword'])) {{$search_parameters['keyword']}} @endif">
            </div>

            <div class="form-group">
                <label>ジャンル</label>
                <div>
                    <input type="checkbox" name="tag[]" value="1" id="jap" class="genre" {{ !empty($search_parameters['tags']) && in_array((string)1, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="jap" class="genre checkbox-label">和</label>
                    <input type="checkbox" name="tag[]" value="2" id="wes" class="genre" {{ !empty($search_parameters['tags']) && in_array((string)2, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="wes" class="genre checkbox-label">洋</label>
                    <input type="checkbox" name="tag[]" value="3" id="chi" class="genre" {{ !empty($search_parameters['tags']) && in_array((string)3, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="chi" class="genre checkbox-label">中</label>
                </div>
            </div>

            <div class="form-group">
                <label>カテゴリ</label>
                <div>
                    <input type="checkbox" name="tag[]" value="4" id="main" class="category" {{ !empty($search_parameters['tags']) && in_array((string)4, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="main" class="category checkbox-label">主<span class="tooltip">主菜</span></label>
                    <input type="checkbox" name="tag[]" value="5" id="side" class="category" {{ !empty($search_parameters['tags']) && in_array((string)5, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="side" class="category checkbox-label">副<span class="tooltip">副菜</span></label>
                    <input type="checkbox" name="tag[]" value="6" id="soup" class="category" {{ !empty($search_parameters['tags']) && in_array((string)6, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="soup" class="category checkbox-label">汁<span class="tooltip">汁物</span></label>
                    <input type="checkbox" name="tag[]" value="7" id="sweets" class="category" {{ !empty($search_parameters['tags']) && in_array((string)7, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="sweets" class="category checkbox-label">菓<span class="tooltip">お菓子</span></label>
                </div>
            </div>
            
            <div class="form-group">
                <label>タグ</label>
                <div>
                    @if(count($tags) == 0)
                        <a href="{{url('items/editTag')}}">タグを追加する</a>
                    @else
                        @foreach($tags as $tag)
                            <input type="checkbox" name="tag[]" value="{{$tag->id}}" id="tag{{$tag->id}}" class="tag" {{ !empty($search_parameters['tags']) && in_array((string)$tag->id, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="tag{{$tag->id}}" class="tag checkbox-label"><i class="view_icon">{{$tag->icon}}</i><span class="tooltip">{{$tag->tag}}</span></label>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label>点数</label>
                <div>
                    <select name="min_score">
                        @for($i=0; $i<=100; $i++)
                            <option value="{{$i}}" {{ !empty($search_parameters['min_score']) && $i == $search_parameters['min_score'] ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                    <span>～</span>
                    <select name="max_score">
                        @for($i=100; $i>=0; $i--)
                            <option value="{{$i}}" {{ !empty($search_parameters['max_score']) && $i == $search_parameters['max_score'] ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group" id="sort-form">
                    <select name="sort" id="sort-item">
                        <option value="新しい順" {{ !empty($search_parameters['sort']) && '新しい順' == $search_parameters['sort'] ? 'selected' : ''}}>新しい順</option>
                        <option value="古い順" {{ !empty($search_parameters['sort']) && '古い順' == $search_parameters['sort'] ? 'selected' : ''}}>古い順</option>
                        <option value="点数順" {{ !empty($search_parameters['sort']) && '点数順' == $search_parameters['sort'] ? 'selected' : ''}}>点数順</option>
                    </select>
                </div>
            </div>

            <input type="submit" value="検索">
        </form>
    </div>
</div>