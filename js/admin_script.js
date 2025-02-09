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

/*-------------counter--------------- */
(()=>{
    const counter=document.querySelectorAll('.counter');
    const array=Array.from(counter);
    array.map((item)=>{
        let counterInnerText=item.textContent;
        item.textContent=0;
        let count=1;
        let speed=item.dataset.speed / counterInnerText;
        function counterUp(){
            item.textContent=count++;
            if(counterInnerText<count){
                clearInterval(stop)
            }
        }
        const stop=setInterval(()=>{
            counterUp();
        },speed)
    })
})