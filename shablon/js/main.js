
// selector =============
const selectElement = function (element) {
    return document.querySelector(element);
};
// selector end=============


//  Fixed header ====================================
window.onscroll = function () {
    scrollFunc();
};

let header = document.getElementById("head-top");
let nav = document.getElementById("nav");
let fix = nav.nextElementSibling;
function scrollFunc() {
    if (window.pageYOffset > fix.offsetTop) {
        header.classList.add("stick");
        nav.classList.add("stick-2");
        selectElement('.top_btn').classList.add('active');
    } else {
        header.classList.remove("stick");
        nav.classList.remove("stick-2");
        selectElement('.top_btn').classList.remove('active');
    }
}
//  Fixed header end ====================================


// timing ====================
function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}
// timing ====================


window.onclick = function (e) {
    if (selectElement('.register').classList.contains('active') && !e.target.closest('.register_body')) {
        selectElement('.register').classList.remove('active');
    }

    if (selectElement('.links').classList.contains('active')) {
        selectElement('.links').classList.remove('active');
    }

    if (selectElement('.links_bg').classList.contains('active')) {
        selectElement('.links_bg').classList.remove('active');
    }

}


// Clicks ================================================================

document.querySelectorAll('.register_btn').forEach(x =>{
    x.addEventListener('click', function () {
        sleep(2).then(() => {
            selectElement('.register').classList.toggle('active');
            selectElement('#btn-2').classList.add('active');
            selectElement('#btn-1').classList.remove('active');
    
            selectElement('.register_content').classList.add('active');
            selectElement('.register_content_2').classList.remove('active');
        });
    });
})

document.querySelectorAll('.log_in').forEach(x =>{
x.addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('.register').classList.toggle('active');
        selectElement('#btn-1').classList.add('active');
        selectElement('#btn-2').classList.remove('active');

        selectElement('.register_content').classList.remove('active');
        selectElement('.register_content_2').classList.add('active');
    });
});
})

selectElement('#btn-1').addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('#btn-1').classList.add('active');
        selectElement('#btn-2').classList.remove('active');

        selectElement('.register_content').classList.remove('active');
        selectElement('.register_content_2').classList.add('active');

    });
});

selectElement('#btn-2').addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('#btn-2').classList.add('active');
        selectElement('#btn-1').classList.remove('active');

        selectElement('.register_content').classList.add('active');
        selectElement('.register_content_2').classList.remove('active');
    });
});

selectElement('.burger').addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('.links_bg').classList.toggle('active');
        selectElement('.links').classList.toggle('active');

    });
});


// Clicks end ================================================================


// Sort by ==============================================================
selectElement('#inline').addEventListener('click', () => {
    document.documentElement.setAttribute("data-theme", "row");
    localStorage.setItem("theme", "row");
    selectElement('#inline').style.opacity = 1;
    selectElement('#card').style.opacity = .5;
    selectElement('.item_btn').style.display = 'none';

    document.querySelectorAll('.item_btn').forEach(el => { el.style.display = 'none'; })
    document.querySelectorAll('.item_head').forEach(el => { el.style.display = 'none'; })
    document.querySelectorAll('.item_sub_name').forEach(el => { el.style.display = 'none'; })
    document.querySelectorAll('.inline_head').forEach(el => { el.style.display = 'flex'; })
    document.querySelectorAll('.inline_num').forEach(el => { el.style.display = 'flex'; })

})

selectElement('#card').addEventListener('click', () => {
    document.documentElement.setAttribute("data-theme", "col");
    selectElement('#inline').style.opacity = .5;
    selectElement('#card').style.opacity = 1;
    document.querySelectorAll('.item_btn').forEach(el => { el.style.display = 'block'; })
    document.querySelectorAll('.item_head').forEach(el => { el.style.display = 'block'; })
    document.querySelectorAll('.item_sub_name').forEach(el => { el.style.display = 'block'; })
    document.querySelectorAll('.inline_head').forEach(el => { el.style.display = 'none'; })
    document.querySelectorAll('.inline_num').forEach(el => { el.style.display = 'none'; })
})

// Sort by end ==============================================================

// Category Tabs ==========================================
const tabsBtn = document.querySelectorAll(".tab_link");
const tabsItems = document.querySelectorAll(".tab_source");
tabsBtn.forEach((e) => {
    onTabClick(tabsBtn, tabsItems, e);
});
function onTabClick(tabBtns, tabItems, item) {
    item.addEventListener("click", function (e) {
        let currentBtn = item;
        let tabId = currentBtn.getAttribute("data-tab");
        let currentTab = document.querySelector(tabId);
        if (e.srcElement.classList.contains("active")) {
            // e.srcElement.classList.remove("active");
            // e.srcElement.parentElement
            //     .querySelector(".tab__btn")
            //     .classList.remove("active");
            // console.log(e.srcElement.parentElement.querySelector(".event"));
            // e.srcElement.parentElement
            //     .querySelector(".event")
            //     .classList.remove("active");
        } else if (!currentBtn.classList.contains("active")) {
            tabBtns.forEach(function (item) {
                item.classList.remove("active");
            });
            tabItems.forEach(function (item) {
                item.classList.remove("active");
            });
            currentBtn.classList.add("active");
            currentTab.classList.add("active");
        }
    });
}

// Category Tabs end ==========================================
