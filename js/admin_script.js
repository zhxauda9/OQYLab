let profile = document.querySelector('header .flex .profile');
let searchForm = document.querySelector('.header .flex .search-form');
let sideBar = document.querySelector('.side-bar');
let body = document.body;

document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
}

document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
}

document.querySelector('#menu-btn').onclick = () => {
    sideBar.classList.toggle('active');
}
window.onscroll = () => {
    profile.classList.remove('active');
    searchForm.classList.remove('active');
    if (window.innerWidth < 1200) {
        sideBar.classList.remove('active');
    }
}
