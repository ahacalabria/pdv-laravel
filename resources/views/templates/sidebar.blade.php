 <div class="col-sm-3 col-md-2 sidebar">
   <ul class="nav nav-sidebar">
   @foreach ($menu as $info)
    <li class="{{{$info['active'] or ""}}}"><a href="#">{{$info['opcao']}} <span class="sr-only">(current)</span></a></li>
  @endforeach
  </ul>
</div>
