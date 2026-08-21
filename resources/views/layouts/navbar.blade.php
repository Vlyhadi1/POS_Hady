<style>
:root {
    --dark-1: #020617;
    --dark-2: #0f172a;
    --dark-3: #1e293b;

    --green: #22c55e;
    --green-dark: #15803d;

    --danger: #ef4444;
}


/* ==========================
   NAVBAR
========================== */

.navbar-custom {

    background:
    linear-gradient(
        135deg,
        var(--dark-1),
        var(--dark-2) 55%,
        var(--dark-3)
    ) !important;


    box-shadow:
    0 15px 45px rgba(0,0,0,.45);


    backdrop-filter:
    blur(18px);


    border-bottom:
    1px solid rgba(255,255,255,.1);


    position:relative;

    overflow:hidden;
}



.navbar-custom::after {

    content:"";

    position:absolute;

    bottom:0;
    left:0;


    width:100%;
    height:3px;


    background:
    linear-gradient(
        90deg,
        transparent,
        var(--green),
        #86efac,
        transparent
    );


    animation:
    glowLine 5s linear infinite;

}



@keyframes glowLine {

    from {
        transform:translateX(-100%);
    }

    to {
        transform:translateX(100%);
    }

}



/* ==========================
   LOGO
========================== */


.navbar-brand-logo {

    width:48px;

    height:48px;


    border-radius:16px;


    display:flex;

    align-items:center;

    justify-content:center;


    background:

    linear-gradient(
        145deg,
        var(--green),
        var(--green-dark)
    );


    box-shadow:

    0 0 0 4px rgba(34,197,94,.15),

    0 15px 35px rgba(34,197,94,.45);


    overflow:hidden;


    position:relative;


    transition:.35s;

}



.navbar-brand-logo::before {

    content:"";


    position:absolute;


    width:100px;

    height:30px;


    background:
    rgba(255,255,255,.25);


    transform:
    rotate(-45deg)
    translate(-80px);


    transition:.5s;

}



.navbar-brand:hover 
.navbar-brand-logo::before {

    transform:
    rotate(-45deg)
    translate(80px);

}



.navbar-brand:hover 
.navbar-brand-logo {

    transform:
    translateY(-3px)
    scale(1.05);

}




.brand-title {

    font-size:1.3rem;

    font-weight:900;

    letter-spacing:1px;


    background:

    linear-gradient(
        90deg,
        white,
        #86efac
    );


    -webkit-background-clip:text;

    -webkit-text-fill-color:transparent;

}



/* ==========================
   MENU
========================== */


.nav-link-custom {


    color:
    rgba(255,255,255,.7)
    !important;


    padding:
    .7rem 1.15rem !important;


    border-radius:14px;


    display:flex;


    align-items:center;


    gap:.6rem;


    font-weight:600;


    transition:.3s;


    position:relative;


    overflow:hidden;

}



.nav-link-custom i {

    font-size:1.1rem;

    transition:.3s;

}



.nav-link-custom:hover {


    color:white !important;


    background:

    rgba(34,197,94,.15);


    transform:
    translateY(-2px);

}



.nav-link-custom:hover i {

    transform:
    scale(1.2);

}




/* ACTIVE MENU */


.nav-link-custom.active {


    color:white !important;


    background:

    linear-gradient(
        135deg,
        var(--green),
        var(--green-dark)
    );


    box-shadow:

    0 10px 30px
    rgba(34,197,94,.4);


    transform:
    translateY(-2px);

}





/* ==========================
   USER PROFILE
========================== */


.user-profile-card {


    background:

    rgba(255,255,255,.08);


    border:

    1px solid
    rgba(255,255,255,.15);



    backdrop-filter:
    blur(15px);



    border-radius:20px;



    padding:

    .4rem .5rem .4rem 1rem;



    display:flex;


    align-items:center;


    gap:.8rem;



    transition:.3s;

}



.user-profile-card:hover {


    background:

    rgba(255,255,255,.14);


    transform:

    translateY(-2px);

}





.user-avatar-icon {


    width:40px;

    height:40px;


    border-radius:50%;



    display:flex;


    align-items:center;


    justify-content:center;



    color:white;



    background:

    linear-gradient(
        135deg,
        var(--green),
        var(--green-dark)
    );


    box-shadow:

    0 5px 20px
    rgba(34,197,94,.4);

}



/* ==========================
   LOGOUT
========================== */


.btn-logout-custom {


    width:38px;


    height:38px;


    border-radius:50%;


    display:flex;


    align-items:center;


    justify-content:center;


    color:white !important;


    background:

    rgba(255,255,255,.1);



    border:

    1px solid
    rgba(255,255,255,.2);



    transition:.35s;

}



.btn-logout-custom:hover {


    background:

    var(--danger) !important;



    box-shadow:

    0 0 25px
    rgba(239,68,68,.6);



    transform:

    rotate(90deg);

}




/* MOBILE */


@media(max-width:991px){


.navbar-collapse {


    background:

    rgba(2,6,23,.95);



    padding:20px;


    border-radius:20px;


    margin-top:15px;


    box-shadow:

    0 20px 50px rgba(0,0,0,.5);

}


}

</style>


<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-2">

<div class="container-fluid px-4">


<a class="navbar-brand d-flex align-items-center gap-3 me-4"
href="{{ route('dashboard') }}">


<div class="navbar-brand-logo">

  <img src="{{ asset('images/vly.png') }}" alt="Logo Vlyhadi" class="w-100 h-100 object-fit-cover">

</div>


<div>

<span class="brand-title">
        Vlyhadi
</span>

</div>


</a>



<button class="navbar-toggler border-0"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarSupportedContent">

<span class="navbar-toggler-icon"></span>

</button>




<div class="collapse navbar-collapse"
id="navbarSupportedContent">



<ul class="navbar-nav me-auto gap-2">


<li class="nav-item">

<a class="nav-link-custom {{ Request::is('dashboard*') ? 'active':'' }}"
href="{{ route('dashboard') }}">

<i class="bi bi-grid-fill"></i>

Dashboard

</a>

</li>


@php
    $userRole = '';

    if(Auth::check() && Auth::user()->role){
        $userRole = strtolower(Auth::user()->role->name);
    }
@endphp


@if(Auth::check() && $userRole == 'admin')

<li class="nav-item">

<a class="nav-link-custom {{ Request::is('admin/users*')?'active':'' }}"
href="{{ route('admin.users') }}">

<i class="bi bi-people-fill"></i>

Users

</a>

</li>

@endif



<li class="nav-item">


<a class="nav-link-custom {{ Request::is('produk*')?'active':'' }}"
href="{{ route('produk.index') }}">


<i class="bi bi-box-seam-fill"></i>

Produk


</a>


</li>




<li class="nav-item">


<a class="nav-link-custom {{ Request::is('penjualan*')?'active':'' }}"
href="{{ route('penjualan.index') }}">


<i class="bi bi-cart-check-fill"></i>

Penjualan


</a>


</li>



</ul>





@auth


<div class="user-profile-card">


<div class="text-end">


<div class="text-white fw-bold">

{{ Auth::user()->name }}

</div>


<small class="text-white-50 text-uppercase">

{{ optional(Auth::user()->role)->name ?? 'Staff' }}

</small>


</div>



<div class="user-avatar-icon">

<i class="bi bi-person-fill"></i>

</div>




<form action="{{ route('logout') }}"
method="POST">

@csrf


<button class="btn btn-logout-custom"
title="Logout">


<i class="bi bi-power"></i>


</button>


</form>



</div>


@endauth



</div>


</div>

</nav>