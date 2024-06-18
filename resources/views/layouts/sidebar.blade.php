@if($browser == 'sp')
<div class="side offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
@else
<div class="side">
@endif
    <div class="side-inner">
        @if($browser == 'sp')
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">レシピを検索</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @endif
        <form action="/items/search" method="get">
            @csrf
            <div class="form-group mb-3">
                <input type="search" name="keyword" placeholder="&#xf002; キーワード" value="@if(isset($search_parameters['keyword'])) {{$search_parameters['keyword']}} @endif" class="keyword">
            </div>

            <div class="form-group mb-1">
                <label>ジャンル</label>
                <div>
                    @foreach($genre_tags as $tag)
                        <input type="checkbox" name="tag[]" value="{{$tag->id}}" id="default-{{$tag->id}}" class="genre" {{ !empty($search_parameters['tags']) && in_array((string)$tag->id, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="default-{{$tag->id}}" class="genre checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="form-group mb-1">
                <label>カテゴリ</label>
                <div>
                    @foreach($category_tags as $tag)
                        <input type="checkbox" name="tag[]" value="{{$tag->id}}" id="default-{{$tag->id}}" class="category" {{ !empty($search_parameters['tags']) && in_array((string)$tag->id, $search_parameters['tags'], true) ? 'checked' : ''}}><label for="default-{{$tag->id}}" class="category checkbox-label">{{$tag->icon}}<span class="tooltip">{{$tag->tag}}</span></label>
                    @endforeach
                </div>
            </div>
            
            <div class="form-group mb-3">
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

            <div class="form-group mb-3">
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
            </div>
            
            <div class="form-group mb-3" id="sort-form">
                <select name="sort" id="sort-item">
                    <option value="新しい順" {{ !empty($search_parameters['sort']) && '新しい順' == $search_parameters['sort'] ? 'selected' : ''}}>新しい順</option>
                    <option value="古い順" {{ !empty($search_parameters['sort']) && '古い順' == $search_parameters['sort'] ? 'selected' : ''}}>古い順</option>
                    <option value="点数順" {{ !empty($search_parameters['sort']) && '点数順' == $search_parameters['sort'] ? 'selected' : ''}}>点数順</option>
                </select>
            </div>

            <input type="submit" value="検索">
        </form>
    </div>
</div>