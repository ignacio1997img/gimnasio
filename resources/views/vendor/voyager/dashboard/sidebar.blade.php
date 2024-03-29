<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('voyager.profile') }}">
                    @php
                        $busine=null;
                    @endphp
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Voyager::setting('admin.icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            @php
                                $user = \Auth::user();
                                if($user->busine_id)
                                {
                                    $busine = \App\Models\Busine::find($user->busine_id);
                                    $image = 'storage/'.str_replace('.', '-cropped.', $busine->image);
                                }
                                else {
                                    $image = 'images/icon.png';
                                }
                            @endphp
                            <img src="{{ asset($image) }}" alt="Logo Icon">
                           
                            {{-- <img src="{{auth()->user()->hasRole('admin')? asset('images/icon.png'):asset('storage/'.str_replace('.', '-cropped.', \App\Model\User::w)) }}" alt="Logo Icon"> --}}
                            {{-- <img src="{{ asset('images/icon.png') }}" alt="Logo Icon"> --}}
                        @else
                            <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{$busine?$busine->name : Voyager::setting('admin.title', 'VOYAGER')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Voyager::image( Voyager::setting('admin.bg_image'), asset('images/banner.jpg') ) }}); background-size: cover; background-position: 0px;">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4>{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('voyager.profile') }}" class="btn btn-primary">{{ __('voyager::generic.profile') }}</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>
        <div id="adminmenu">
            <admin-menu :items="{{ menu('admin', '_json') }}"></admin-menu>
        </div>
    </nav>
</div>
