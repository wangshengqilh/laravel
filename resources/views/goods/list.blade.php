@extends('layouts.bst')

@section('content')
    <div class="container">
        <ul>
            <input type="text" id="name"><input type="button" value="搜索" id="sou">
            @foreach($list as $k=>$v)
                <li> 商品ID：{{$v->goods_id}}  --  商品价格：¥ {{$v->price}}
                    <a href="/goods/detail/{{$v->goods_id}}" >{{$v->goods_name}}</a><br><br>
                </li>
            @endforeach
        </ul>
    </div>
    {{$list->links()}}
@endsection
<button>座位1</button><button>座位2</button><button>座位3</button><button>座位4</button><button>座位5</button><button>座位6</button><button>座位7</button><button>座位8</button><button>座位9</button><button>座位10</button><button>座位11</button><button>座位12</button><button>座位13</button><button>座位14</button><button>座位15</button><button>座位16</button><button>座位17</button>

@section('footer')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection