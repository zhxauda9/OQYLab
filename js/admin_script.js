let profile=document.querySelector('header .flex .profile');

document.querySelector('#user-btn').onclick=() =>{
    profile.classList.toggle('active');
}

let searchForm=document.querySelector('.header .flex .search-form');
document.querySelector('#search-btn').onlick = () =>{
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
}

let sideBar=document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick= () =>{
    sideBar.classList.toggle('active');
    body.classList.toggle('active');
}

window.onscroll= () =>{
    profile.classList.remove('active');
    searchForm.classList.remove('active');

    if(window.innerWidth<1200){
        sideBar.classList.remove('active');
        body.classList.remove('active');
    }
}