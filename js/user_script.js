let profile=document.querySelector('.header .flex .profile');
document.querySelector('#user-btn').onclick=()=>{
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
}

let searchForm=document.querySelector('.header .flex .search-form');
document.querySelector('#search-btn').onlick=()=>{
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
}

let navbar=document.querySelector('.header .navbar');
document.querySelector('#menu-btn').onclick=()=>{
    navbar.classList.toggle('active');
}


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
})()

const tabsContainer = document.querySelector('.teacher-tabs');
const aboutSection = document.querySelector('.teacher-section');

tabsContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('tab-item') && !e.target.classList.contains('active')) {
        tabsContainer.querySelector('.active').classList.remove('active');
        e.target.classList.add('active');
        const target = e.target.getAttribute('data-target');

        const activeTab = aboutSection.querySelector('.tab-content.active');
        if (activeTab) {
            activeTab.classList.remove('active');
        }
        aboutSection.querySelector(target).classList.add('active');
    }
});
