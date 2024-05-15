<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="{{ Request::segment(1) == '' ? 'active' : '' }}"><a
                            href="{{ route('dashboard.index') }}"><i class="icon-Layout-4-blocks"><span
                                    class="path1"></span><span class="path2"></span></i>Dashboard</a></li>
                    @if (authUser()->role == 'admin')
                        <li class="{{ Request::segment(1) == 'laporan' ? 'active' : '' }}"><a
                                href="{{ route('laporan.index') }}"><i class="icon-Layout-4-blocks"><span
                                        class="path1"></span><span class="path2"></span></i>Laporan</a></li>
                    @endif
                    <li class="treeview {{ Request::segment(1) == 'pemesanan' ? 'active' : '' }}">
                        <a href="#">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            <span>Pemesanan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (authUser()->role == 'admin')
                                <li
                                    class="{{ Request::segment(1) == 'pemesanan' && Request::segment(2) == 'create' ? 'active' : '' }}">
                                    <a href="{{ route('pemesanan.create') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Buat Pesanan</a>
                                </li>
                            @endif
                            <li
                                class="{{ Request::segment(1) == 'pemesanan' && Request::segment(2) != 'create' ? 'active' : '' }}">
                                <a href="{{ route('pemesanan.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Data Pesanan</a>
                            </li>
                        </ul>
                    </li>
                    @if (authUser()->role == 'admin')
                        <li
                            class="treeview {{ Request::segment(1) == 'user' || Request::segment(1) == 'kendaraan' ? 'active' : '' }}">
                            <a href="#">
                                <i class="icon-Layout-4-blocks"><span class="path1"></span><span
                                        class="path2"></span></i>
                                <span>Data Master</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="{{ Request::segment(1) == 'user' ? 'active' : '' }}"><a
                                        href="{{ route('user.index') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>User</a></li>
                                <li class="{{ Request::segment(1) == 'kendaraan' ? 'active' : '' }}"><a
                                        href="{{ route('kendaraan.index') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Kendaraan</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </section>
</aside>
