
//


// selector =============
const selectElement = function (element) {
    return document.querySelector(element);
};
// selector end=============

let drop = document.querySelector('.profile_drop');
let profile_head = document.querySelector('.profile_head');
let register = document.querySelector('.register');
let register_btn = document.querySelectorAll('.register_btn');
let log_in = document.querySelectorAll('.log_in');
let btn_1 = document.querySelector('#btn-1');
let btn_2 = document.querySelector('#btn-2');

let inline = document.querySelector('#inline');
let card = document.querySelector('#card');

let forget = document.querySelector('#forget');
let password = document.querySelector('.password');

let eye_off = document.querySelectorAll('.eye_off');
let eye_on = document.querySelectorAll('.eye_on');
let pass = document.querySelector('#password');
let pass_2 = document.querySelector('#password_2');
let person = document.querySelectorAll('.person');
let chat_alert = document.querySelectorAll('.chat_alert');

let chat_burger = document.querySelector('.chat_burger');
let chat_people = document.querySelector('.chat_people');

let register_content = document.querySelector('.register_content');
let register_content_2 = document.querySelector('.register_content_2');

let seller_btn = document.querySelector('.seller_btn');
let seller_info = document.querySelector('.seller_info');

let mobile_user_profile = document.querySelector('.mobile_user-profile');
let mobile_profile_navs = document.querySelector('.mobile_profile-navs');

let notification_header = document.querySelector('.notification_header');
let notification_area = document.querySelector('.notification_area');

let phone_box = document.querySelectorAll('.phone_box');
let iti__country = document.querySelectorAll('.iti__country');
let iti__country_list = document.querySelectorAll('.iti__country-list');


let delete_btn = document.querySelectorAll('.delete');
let delete_modal = document.querySelector('#delete-modal');


//  Fixed header ====================================

window.onscroll = function () {
    scrollFunc();

    // drop.forEach(drop => {
    //     if (drop != undefined) {
    //         drop.classList.remove('active');
    //     }
    // }
    // );

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

// Accordion =============================


var accordion = document.getElementsByClassName("accord");
var z;

for (z = 0; z < accordion.length; z++) {
    accordion[z].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;


        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
            panel.style.overflow = "auto";
            panel.style.marginTop = "0px";

        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
            panel.style.overflow = "visible";
            panel.style.marginTop = "10px";
        }
    });
} // Accordion end =========================


window.onclick = function (e) {

    if (drop != undefined) {
        if (drop.classList.contains('active') && !e.target.closest('.profile_head')) {
            drop.classList.remove('active');
        }
    }

    if (delete_modal != undefined) {
        if (delete_modal.classList.contains('active')) {
            delete_modal.classList.remove('active');
        }
    }

    // if (iti__country_list != undefined) {
    //     if (iti__country_list.classList.contains('active') && !e.target.closest('.phone_box')) {
    //         iti__country_list.classList.remove('active');
    //     }
    // }

    if (notification_area != undefined) {
        if (notification_area.classList.contains('active') && !e.target.closest('.notification_header')) {
            notification_area.classList.remove('active');
        }
    }

    // drop.forEach(drop => {
    //     if (drop != undefined) {
    //         drop.classList.contains('active');
    //         drop.classList.remove('active')
    //     }
    // }
    // );

    // if (iti__country_list != undefined) {
    //     iti__country_list.forEach(er => {
    //         if (er.classList.contains('active') && !e.target.closest('.phone_box')); {
    //             er.classList.remove('active')
    //         }
    //     });
    // }


    if (register != undefined) {
        if (register.classList.contains('active') && !e.target.closest('.register_body')) {
            register.classList.remove('active');
            selectElement('body').classList.remove('active');

        }
    }

    if (forget != undefined) {
        if (password.classList.contains('active') && !e.target.closest('.pass_mail') && !e.target.closest('.pass_mail')) {
            password.classList.remove('active');
        }
    }

    if (chat_people != undefined) {
        if (chat_people.classList.contains('active') && !e.target.closest('.chat_burger')) {
            chat_people.classList.remove('active');
        }
    }

    if (mobile_profile_navs != undefined) {
        if (mobile_profile_navs.classList.contains('active') && !e.target.closest('.mobile_profile-inner')) {
            mobile_profile_navs.classList.remove('active');
        }
    }

    if (seller_info != undefined) {
        if (seller_info.classList.contains('active') && !e.target.closest('.seller_inner')) {
            seller_info.classList.remove('active');
            selectElement('body').classList.remove('active');
        }
    }

    if (selectElement('.links').classList.contains('active') && !e.target.closest('.profile_head') && !e.target.closest('.links')) {
        selectElement('.links').classList.remove('active');
        selectElement('body').classList.remove('active');
    }

    if (selectElement('.links_bg').classList.contains('active') && !e.target.closest('.profile_head') && !e.target.closest('.links')) {
        selectElement('.links_bg').classList.remove('active');
        selectElement('body').classList.remove('active');
    }


}


// Clicks ================================================================

selectElement('.burger').addEventListener('click', function () {
    sleep(2).then(() => {
        selectElement('.links_bg').classList.toggle('active');
        selectElement('.links').classList.toggle('active');
        selectElement('body').classList.toggle('active');
    });
});

// if (profile_head != undefined) {
//     profile_head.forEach(r => {
//         r.addEventListener('click', function () {
//             sleep(2).then(() => {
//                 drop.forEach(p => {
//                     p.classList.toggle('active');
//                 })
//             })
//         });
//     });
// }

if (profile_head != undefined) {
    profile_head.addEventListener('click', function () {
        sleep(2).then(() => {
            drop.classList.toggle('active');
        });
    });
}

// if (phone_box != undefined) {
//     phone_box.addEventListener('click', function () {
//         sleep(2).then(() => {
//             iti__country_list.classList.toggle('active');
//         });
//     });
// }

if (phone_box != undefined) {
    phone_box.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                iti__country_list.forEach(e => {
                    e.classList.toggle('active');
                })
            });
        });
    })
}

if (delete_btn != undefined) {
    delete_btn.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                delete_modal.classList.add('active');
            });
        });
    })
}

if (iti__country != undefined) {
    iti__country.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                iti__country_list.forEach(e => {
                    e.classList.remove('active');
                })
            });
        });
    })
}


if (notification_header != undefined) {
    notification_header.addEventListener('click', function () {
        sleep(2).then(() => {
            notification_area.classList.toggle('active');
        });
    });
}


if (mobile_user_profile != undefined) {
    mobile_user_profile.addEventListener('click', function () {
        sleep(2).then(() => {
            mobile_profile_navs.classList.add('active');
        });
    });
}

if (register_btn != undefined) {
    register_btn.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {
                register.classList.toggle('active');
                btn_2.classList.add('active');
                btn_1.classList.remove('active');
                selectElement('body').classList.add('active');


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
                selectElement('body').classList.add('active');


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

if (forget != undefined) {
    forget.addEventListener('click', function () {
        sleep(2).then(() => {
            password.classList.add('active');

            register.classList.remove('active');
        });
    });
}

if (eye_off != undefined) {
    eye_off.forEach(x => {
        x.addEventListener('click', function () {
            sleep(2).then(() => {

                pass.type = "text";
                pass_2.type = "text";

                eye_on.forEach(e => {
                    e.classList.add('active');
                })
                eye_off.forEach(w => {
                    w.classList.add('active');
                })
            });
        });
    })
}

if (eye_on != undefined) {
    eye_on.forEach(q => {
        q.addEventListener('click', function () {
            sleep(2).then(() => {

                pass.type = "password";
                pass_2.type = "password";

                eye_on.forEach(e => {
                    e.classList.remove('active');
                })
                eye_off.forEach(w => {
                    w.classList.remove('active');
                })
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

if (chat_burger != undefined) {
    chat_burger.addEventListener('click', function () {
        sleep(2).then(() => {
            chat_people.classList.toggle('active');
        });
    });
}

if (seller_btn != undefined) {
    seller_btn.addEventListener('click', function () {
        sleep(2).then(() => {
            seller_info.classList.add('active');
            selectElement('body').classList.add('active');
        });
    });
}

// if (person != undefined) {
//     person.forEach(y => {
//         y.addEventListener('click', function () {
//             sleep(2).then(() => {
//                 // chat_alert.forEach(e => {
//                 //     e.classList.add('active');
//                 // })

//                 chat_alert.classList.add('active');
//             });
//         });
//     })
// }


// Clicks end ================================================================


// Category Tabs ==========================================
// const tabsBtn = document.querySelectorAll(".tab_link");
// const tabsItems = document.querySelectorAll(".tab_source");

// const tabsBtn = document.querySelectorAll(".tab_btn");
// const tabsItems = document.querySelectorAll(".tab_info");
// tabsBtn.forEach((e) => {
//     onTabClick(tabsBtn, tabsItems, e);
// });
// function onTabClick(tabBtns, tabItems, item) {
//     item.addEventListener("click", function (e) {
//         let currentBtn = item;
//         let tabId = currentBtn.getAttribute("data-tab");
//         let currentTab = document.querySelector(tabId);
//         if (e.srcElement.classList.contains("active")) {
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

// -----------------------------------------------------------------------------------------------
// const tabsBtn_2 = document.querySelectorAll(".open_link");
// const tabsItems_2 = document.querySelectorAll(".open_info");
// tabsBtn_2.forEach((e) => {
//     onTabClick(tabsBtn_2, tabsItems_2, chat_alert, e);

// });
// function onTabClick(tabBtns_2, tabItems_2, chat_alert, item) {
//     item.addEventListener("click", function (e) {
//         let currentBtn_2 = item;
//         let tabId = currentBtn_2.getAttribute("data-tab");
//         let currentTab_2 = document.querySelector(tabId);
//         if (e.srcElement.classList.contains("active")) {
//         } else if (!currentBtn_2.classList.contains("active")) {
//             tabBtns_2.forEach(function (item) {
//                 item.classList.remove("active");
//             });
//             tabItems_2.forEach(function (item) {
//                 item.classList.remove("active");
//             });
//             currentBtn_2.classList.add("active");
//             currentTab_2.classList.add("active");
//         }
//     });
// }

const formBtn = document.querySelectorAll(".tab_btn");
const formItem = document.querySelectorAll(".tab_info");
formBtn.forEach((e) => {
    onTabClick(formBtn, formItem, e);
});
const formOuterBtn = document.querySelectorAll(".open_link");
const formOuterItem = document.querySelectorAll(".open_info");
formOuterBtn.forEach((e) => {
    onTabClick(formOuterBtn, formOuterItem, e);
});
const heroBtn = document.querySelectorAll(".heroBtn");
const heroItem = document.querySelectorAll(".heroItem");
heroBtn.forEach((e) => {
    onTabClick(heroBtn, heroItem, e);
});
function onTabClick(formBtns, formItems, itemForm) {
    itemForm.addEventListener("click", function (e) {
        let currentformBtn = itemForm;
        let tabIdForm = currentformBtn.getAttribute("data-tab");
        let currentformItem = document.querySelector(tabIdForm);
        if (!currentformBtn.classList.contains("active")) {
            formBtns.forEach(function (itemForm) {
                itemForm.classList.remove("active");
            });
            formItems.forEach(function (itemForm) {
                itemForm.classList.remove("active");
            });
            currentformBtn.classList.add("active");
            currentformItem.classList.add("active");
        }
    });
}




// Category Tabs end ==========================================


// Sort by ==============================================================

if (inline != undefined) {
    inline.addEventListener('click', () => {
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
}


if (card != undefined) {
    card.addEventListener('click', () => {
        document.documentElement.setAttribute("data-theme", "col");
        selectElement('#inline').style.opacity = .5;
        selectElement('#card').style.opacity = 1;
        document.querySelectorAll('.item_btn').forEach(el => { el.style.display = 'block'; })
        document.querySelectorAll('.item_head').forEach(el => { el.style.display = 'block'; })
        document.querySelectorAll('.item_sub_name').forEach(el => { el.style.display = 'block'; })
        document.querySelectorAll('.inline_head').forEach(el => { el.style.display = 'none'; })
        document.querySelectorAll('.inline_num').forEach(el => { el.style.display = 'none'; })
    })
}


// Sort by end ==============================================================

