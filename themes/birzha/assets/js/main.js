
// selector =============
const selectElement = function (element) {
    return document.querySelector(element);
};
// selector end=============

let drop = document.querySelectorAll('.profile_drop');
let profile_head = document.querySelectorAll('.profile_head');
let register = document.querySelector('.register');
let register_btn = document.querySelectorAll('.register_btn');
let log_in = document.querySelectorAll('.log_in');
let btn_1 = document.querySelector('#btn-1');
let btn_2 = document.querySelector('#btn-2');

let register_content = document.querySelector('.register_content');
let register_content_2 = document.querySelector('.register_content_2');

//  Fixed header ====================================
window.onscroll = function () {
    scrollFunc();

    if (drop != undefined) {
        drop.classList.remove('active');
    }

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

    if (drop != undefined) {
        if (document.querySelector('.profile_drop').classList.contains('active') && !e.target.closest('.profile_head')) {
            document.querySelector('.profile_drop').classList.remove('active');
        }
    }

    if (register != undefined) {
        if (register.classList.contains('active') && !e.target.closest('.register_body')) {
            register.classList.remove('active');
        }
    }

    if (selectElement('.links').classList.contains('active')&& !e.target.closest('.profile_head')) {
        selectElement('.links').classList.remove('active');
    }

    if (selectElement('.links_bg').classList.contains('active') && !e.target.closest('.profile_head')) {
        selectElement('.links_bg').classList.remove('active');
    }

}


// Clicks ================================================================

selectElement('.burger').addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('.links_bg').classList.toggle('active');
        selectElement('.links').classList.toggle('active');

    });
});

if (profile_head != undefined) {
    profile_head.forEach(r => {
        r.addEventListener('click', function () {
            sleep(2).then(() => {
                drop.forEach(d => {
                    d.classList.toggle('active');
                })
            });
        });
    })
}



if (register_btn != undefined) {
    register_btn.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                register.classList.toggle('active');
                btn_2.classList.add('active');
                btn_1.classList.remove('active');

                register_content.classList.add('active');
                register_content_2.classList.remove('active');
            });
        });
    })
}


if (log_in != undefined) {
    log_in.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                register.classList.toggle('active');
                btn_1.classList.add('active');
                btn_2.classList.remove('active');

                register_content.classList.remove('active');
                register_content_2.classList.add('active');
            });
        });
    })

}


if (btn_1 != undefined) {
    btn_1.addEventListener('click', function () {
        sleep(2).then(() => {
            btn_1.classList.add('active');
            btn_2.classList.remove('active');

            register_content.classList.remove('active');
            register_content_2.classList.add('active');

        });
    });
}



if (btn_2 != undefined) {
    btn_2.addEventListener('click', function () {
        sleep(2).then(() => {
            btn_2.classList.add('active');
            btn_1.classList.remove('active');

            register_content.classList.add('active');
            register_content_2.classList.remove('active');
        });
    });
}



// Clicks end ================================================================


// Category Tabs ==========================================
// const tabsBtn = document.querySelectorAll(".tab_link");
// const tabsItems = document.querySelectorAll(".tab_source");
// tabsBtn.forEach((e) => {
//     onTabClick(tabsBtn, tabsItems, e);
// });
// function onTabClick(tabBtns, tabItems, item) {
//     item.addEventListener("click", function (e) {
//         let currentBtn = item;
//         let tabId = currentBtn.getAttribute("data-tab");
//         let currentTab = document.querySelector(tabId);
//         if (e.srcElement.classList.contains("active")) {
//             // e.srcElement.classList.remove("active");
//             // e.srcElement.parentElement
//             //     .querySelector(".tab__btn")
//             //     .classList.remove("active");
//             // console.log(e.srcElement.parentElement.querySelector(".event"));
//             // e.srcElement.parentElement
//             //     .querySelector(".event")
//             //     .classList.remove("active");
//         } else if (!currentBtn.classList.contains("active")) {
//             tabBtns.forEach(function (item) {
//                 item.classList.remove("active");
//             });
//             tabItems.forEach(function (item) {
//                 item.classList.remove("active");
//             });
//             currentBtn.classList.add("active");
//             currentTab.classList.add("active");
//         }
//     });
// }

// Category Tabs end ==========================================


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

