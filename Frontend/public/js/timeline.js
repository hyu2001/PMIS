(function () {
    const startDatepicker = (node) => $(node).find("input[name='start']");
    const endDateInput = (node) => $(node).find("input[name='end']");
    gantt.form_blocks["datepicker"] = {
        render: (sns) => {
            const height = sns.height || 'auto';
            return "<div class='gantt-lb-datepicker' style='height:" + height + "px;'>" +
                "Star Date: <input type='text' name='start'> End to: " +
                "<input type='text' name='end'>" +
                "</div>";;
        },
        set_value: (node, value, task, section) => {
            const datepickerConfig = {
                format: 'yyyy-mm-dd',
                autoclose: true,
                container: gantt.$container
            };
            startDatepicker(node).datepicker(datepickerConfig);
            startDatepicker(node).datepicker('setDate', value ? value.start_date : task.start_date);
            endDateInput(node).datepicker(datepickerConfig);
            endDateInput(node).datepicker('setDate', value ? value.end_date : task.end_date);

            startDatepicker(node).datepicker().on('changeDate', function (e) {
                const endValue = endDateInput(node).datepicker('getDate');
                const startValue = startDatepicker(node).datepicker('getDate');

                if (startValue && endValue) {
                    if (endValue.valueOf() <= startValue.valueOf()) {
                        endDateInput(node).datepicker('setDate',
                            gantt.calculateEndDate({ start_date: startValue, duration: 1, task: task }));
                    }
                }
            });
        },
        get_value: (node, task, section) => {
            const start = startDatepicker(node).datepicker('getDate');
            let end = endDateInput(node).datepicker('getDate');
            let result = null;
            if (end.valueOf() <= start.valueOf()) {
                end = gantt.calculateEndDate({
                    start_date: start,
                    duration: 1,
                    task: task
                });
            }

            if (task.start_date && task.end_date) {
                task.start_date = start;
                task.end_date = end;
                result = {
                    start_date: start,
                    end_date: end,
                    duration: task.duration
                }
            } else if (task.start_date) {
                task.start_date = start;
                result = {
                    start_date: start,
                    duration: task.duration
                }
            }

            task.duration = gantt.calculateDuration(task);
            return result;
        },
        focus: (node) => {

        }
    }

})();

// end test data
let addButtonStatus = null;
let keys = null;
let taskData;

gantt.config.grid_width = 420;
gantt.config.grid_resize = true;
gantt.config.open_tree_initially = true;

var labels = gantt.locale.labels;
labels.column_priority = labels.section_priority = "Status";
labels.column_owner = labels.section_owner = "Owner";

function byId(list, id) {
    for (var i = 0; i < list.length; i++) {
        if (list[i].key == id)
            return list[i].label || "";
    }
    return "";
}
// var addProjectBtn = '<span data-action="addP"><div class="gantt_grid_head_cell gantt_grid_head_add" onclick="gantt.createTask()"></div></span>';
var addProjectBtn = '<span data-action="addP"><div class="add-project addTag" onclick="gantt.createTask()">+ 新增專案</div></span>';
//生成 add project的按鈕

gantt.serverList("status", [{
    key: 1,
    label: "In"
},
{
    key: 2,
    label: "M"
},
{
    key: 3,
    label: "P"
},
{
    key: 4,
    label: "完"
},
{
    key: 5,
    label: "急"
}
]);

gantt.config.columns = [{
        name: "status",
        align: "center",
        width: 40,
        template: function (item) {
            if (item.type == gantt.config.types.task) {
                return;
            }
            

            // if (item.type == gantt.config.types.project) {
            //     return (
            //         '<span data-action="add">Add task</span>'
            //     );
            // }

            return (
                
                `<span data-action="status">${byId(gantt.serverList('status'), item.status)}</span>`
            );
        }
    },
    {
        name: "text",
        label: " ",
        tree: true,
        width: 200
    },
    {
        name: "owner",
        width: 80,
        label: " ",
        align: "center",
        template: function (item) {
            return byId(gantt.serverList('staff'), item.owner_id)
        }
    },
    {
        name: "buttons",
        label: addProjectBtn,
        width: 150,
        template: function (item) {
            if (item.type == gantt.config.types.project) {
                return (
                    '<span data-action="add"><div class="gantt_grid_head_cell gantt_grid_head_add addTag"></div></span>'
                    //生成 add task的按鈕
                    );
            }

            return;
        }
    }
];

gantt.attachEvent("onTaskDblClick", function (id, e) {
    console.log("onTaskDbClick");

    // let checkTaskTypes = /^[a-zA-Z]/.test(id);
    // console.log(checkTaskTypes, id)
    // if (checkTaskTypes) {

    //     let newId = id.slice(0, 1);
    //     console.log(newId);
    //     switch (newId) {
    //         case 't':
    //             gantt.locale.labels.section_description = "Task Title"; // 更改 Descript title 名稱
    //             break;
    //         case 'm':
    //             gantt.locale.labels.section_description = "Milestone Title"; // 更改 Descript title 名稱
    //             break;
    //         default:
    //             gantt.locale.labels.section_description = "Title"; // 更改 Descript title 名稱
    //             break;
    //     }

    // } else {
    //     gantt.locale.labels.section_description = "Project Title"; // 更改 Descript title 名稱

    // }


    var button = e.target.closest("[data-action]");
    if (button) {
        console.log("enter db");
        var action = button.getAttribute("data-action");
        switch (action) {
            case "edit":
                console.log('edit');
                gantt.showLightbox(id);
                break;
            case "status":
                console.log('change status');
                var taskId = null;
                
                showLightbox(id);
                
                function showLightbox(id) {
                    taskId = id;
                    var task = gantt.getTask(id);
                    replaceMyForm(id,task);
                    var form = getForm();
                    // var input = form.querySelector(".task-title");

                    // input.innerHTML = task.text
                 
                    form.style.display = "block";
                    document.querySelector('.overlay').style.display = 'block';
                 
                    // form.querySelector(".btn-change-save").onclick = save;
                    // form.querySelector(".btn-change-close").onclick = cancel;
                };

                function replaceMyForm(id,task) {
                    $.ajax({
                      type: "POST",
                      url: "gantt_popup/myform.php", // 將此替換為處理資料的PHP檔案路徑
                      data: { pid: id }, // 傳遞項目的id給後端
                      success: function(response) {
                        // 在此處處理PHP返回的資料
                        $("#my-form").html(response);
                        setEventHandlers(task);
                      },
                      error: function(xhr, status, error) {
                        console.error(error);
                      }
                    });
                }

                // 在替換內容之後重新設定事件處理器
                function setEventHandlers(task) {
                    console.log("This is Handlers");
                    console.log(task.text);
                    var form = document.getElementById("my-form");
                    var input = form.querySelector(".task-title");
                    var saveBtn = form.querySelector(".btn-change-save");
                    var cancelBtn = form.querySelector(".btn-change-close");

                    input.innerHTML = "Project Name：" + task.text;
                    saveBtn.onclick = save;
                    cancelBtn.onclick = cancel;
                }

                function getForm() {
                    return document.getElementById("my-form");
                };
                 
                function save() {  //在編輯status的Lightbox中按下save後，傳送編輯資料讓後端進行資料庫存取
                    var task = gantt.getTask(taskId);
                    //取得下拉選單內容
                    var selectedStatus = document.querySelector("#update_status").value;
                    console.log("status="+selectedStatus);
                    let keys = 
                    {
                        'pid': id,
                        'status': selectedStatus,
                    }
                    fetch('gantt_popup/Dbclick_Update.php', {  
                        //對php發送POST請求
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'  //設定傳送內容為json
                        },
                        body: JSON.stringify(keys)             //傳送內容
                    })

                    let newStatus = getForm().querySelector(".task-status").value;
                    // document.querySelector('input[name=task-id]').value = '';
                    task['status'] = newStatus;
                    gantt.updateTask(taskId);
                    getForm().style.display = 'none';
                    document.querySelector('.overlay').style.display = 'none';
                    //刷新頁面
                    location.href = "welcome.php";
                }
                 
                function cancel() {
                    getForm().style.display = 'none';
                    document.querySelector('.overlay').style.display = 'none';
                }

                break;
                
            case "delete":
                gantt.confirm({
                    title: gantt.locale.labels.confirm_deleting_title,
                    text: gantt.locale.labels.confirm_deleting,
                    callback: function (res) {
                        if (res)
                            gantt.deleteTask(id);
                    }
                });
                break;
        }
        return false;

    }
    return true;
});


$(document).on('click','.addTag',function(){
    let status = $(this).parent().attr('data-action');
    // console.log(`status:${$(this).parent().attr('data-action')}`)
    addButtonStatus = status;
    console.log(addButtonStatus);
    if(addButtonStatus == "add"){
        disabledProjectSelect(addButtonStatus);
    }
});

let saveBtn = $('div[aria-label="Save"]');
saveBtn.on('click',function(){
            console.log("已儲存");
});

gantt.attachEvent("onTaskClick", function (id, e) {
    console.log("onTaskClick " + id)
    var button = e.target.closest("[data-action]")
    var task = gantt.getTask(id);
    if (button) {
        var action = button.getAttribute("data-action");
        switch (action) {
            case "add":
                // console.log('add task');
                gantt.createTask(null, id);
                console.log("pid is "+id);

                // console.log($('.gantt_cal_ltext:nth-child(4) select'))
                // $('.gantt_cal_ltext:nth-child(4) select').change(function(){
                //     console.log($(this).find(":selected").val())
                // });

                // $(document).one('click','.gantt_cal_light div[aria-label="Save"]', function(){
                //     // console.log($(this))
                //     let taskDesc = $('.gantt_cal_ltext:nth-child(2) textarea').val();
                //     let taskType = $('.gantt_cal_ltext:nth-child(4) select').find(":selected").val();
                //     let taskOwner = null;
                //     let taskDuration = null;
                //     let taskDay = null;
                //     let taskMonths = null;
                //     let taskYears = null;

                //     if(taskType=="task"){
                //         console.log("task")
                //         taskOwner = $('.gantt_cal_ltext:nth-child(6) select').find(":selected").val();
                //         taskDuration = $('input[aria-label="Duration"]').val();
                //         taskDay = $('select[aria-label="Days"]').find(":selected").val();
                //         taskMonths = $('select[aria-label="Months"]').find(":selected").val();
                //         taskYears = $('select[aria-label="Years"]').find(":selected").val();
                //     }else if(taskType=="milestone"){
                //         console.log("milestone")
                //         taskDay = $('select[aria-label="Days"]').find(":selected").val();
                //         taskMonths = $('select[aria-label="Months"]').find(":selected").val();
                //         taskYears = $('select[aria-label="Years"]').find(":selected").val();
                //     }else{
                //         console.log("add a project")
                //     }

                //     // console.log("已儲存");
                //     let keys = {
                //         'pid':id,
                //         'text': taskDesc,
                //         'type': taskType,
                //         'owner': taskOwner,
                //         'day': taskDay,
                //         'month': taskMonths,
                //         'year': taskYears,
                //         'duration': taskDuration,
                //     }
                    
                //     // map key value
                //     // console.log(
                //     //     taskDesc,taskType,taskOwner,taskDay,taskMonths,taskYears,taskDuration
                //     // )
                    
                //     fetch('gantt_popup/gantt_Create.php', {  //對php發送POST請求
                //         method: 'POST',
                //         headers: {
                //           'Content-Type': 'application/json'  //設定傳送內容為json
                //         },
                //         body: JSON.stringify(keys)            //傳送內容
                //       })
                //       .catch(error => {
                //         console.error('發生錯誤:', error);
                //       });
                      
                // });
                
                break;
        }
        return false;
    }

    //show mark
    if(task.parent == 0){  //點擊的是"專案名稱",因為沒有parent，所以直接取id當作mark參數
        showMark(id);
    }else{                 //點擊的是"子項目",有parent，mark參數用parent的
        showMark(task.parent);  
    }
    
    return true;
});

function showMark(content) {
    $.ajax({
        url: "lib/getmark.php",
        type: "GET",
        data: { pid: content },
        success: function(response) {
            $("#mark-here").html(response);
        },
        error: function(xhr, status, error) {
            console.log("An error occurred: " + error);
        }
    });
}



// $.getJSON('lib/staff_data.json', function(data){
//     gantt.serverList("staff",data)
//     console.log(data);
// });


gantt.config.lightbox.project_sections = [{
    name: "description_p",
    height: 70,
    map_to: "text",
    type: "textarea",
    focus: true
    },
    {
    name: "department",
        height: 22,
        type: "select",
        map_to: "dept_id",
        options: gantt.serverList("dept")
    }
];

gantt.config.lightbox.sections = [{
        name: "description_t",
        height: 70,
        map_to: "text",
        type: "textarea",
        focus: true
    },
    {   name: "type", 
        type: "typeselect", 
        map_to: "type"
    },
    {
        name: "owner",
        height: 22,
        map_to: "owner_id",
        type: "select",
        options: gantt.serverList("staff")
    },
    {
        name: "status",
        height: 22,
        map_to: "task_status",
        type: "checkbox",
        options:[      
            { key: 5, label: "Urgent" }
        ]
    },
    { 
        name: "time", 
        height: 45, 
        map_to: "auto", 
        type: "datepicker",
        hidden: true
    }
];

gantt.config.lightbox.milestone_sections = [{
    name: "description_m",
    height: 70,
    map_to: "text",
    type: "textarea",
    focus: true
},
{   name: "type", 
    type: "typeselect", 
    map_to: "type"
},
{
    name: "status",
    height: 22,
    map_to: "task_status",
    type: "checkbox",
    options:[      
        { key: 5, label: "Urgent" }
    ]
},
{
  name: "time",
  type: "duration",
  single_date: !0,
  map_to: "auto",
}
];

//==========載入資料==========//
// $.ajax({
//     url: 'show_board/gantt_getdata.php',
//     method:'GET',
//     dataType: 'json',
//     success: res =>{
//         console.log(res)
//         taskData = res;
//     },
//     error: err =>{
//         console.log(err)
//     },
// }).then(function(){
//     gantt.updateCollection("staff", taskData.staff);
//     gantt.updateCollection("dept", taskData.dept);
//     // 获取data中的text属性
//     const text = taskData.data[0].text;
//     if (text == '查無資料') {
//         document.getElementById('no-result').innerText = 'No result found';
//     }else{
//         gantt.parse(taskData);
//     }
// });

$.ajax({
    url: 'show_board/notfound.php',
    dataType: 'json',
    success: res =>{
        console.log(res)
        data = res;
    },
    error: err =>{
        console.log(err)
    },
}).then(function(){
    if (data == 0) {
        $.ajax({
            url: 'show_board/deptData.php',
            method:'POST',
            dataType: 'json',
            success: res =>{
                console.log(res)
                taskData = res;
            },
            error: err =>{
                console.log(err)
            },
        }).then(function(){
            gantt.clearAll();
            gantt.updateCollection("staff", taskData.staff);
            gantt.updateCollection("dept", taskData.dept);
            document.getElementById('no-result').innerText = 'No result found';
           })
    }else{
        $.ajax({
            url: 'show_board/gantt_getdata.php',
            method:'GET',
            dataType: 'json',
            success: res =>{
                console.log(res)
                taskData = res;
            },
            error: err =>{
                console.log(err)
            },
        }).then(function(){
            gantt.updateCollection("staff", taskData.staff);
            gantt.updateCollection("dept", taskData.dept);
            gantt.parse(taskData);
            
        });
    }
});


//當被勾選時直接ajax獲取結果而不用再點擊搜尋//
var filter_name;
var filter_value;
var filter_selected;
var is_optgroup;
var is_all;
//Filter-成員:
$('select[name="member[]"]').multipleSelect({
    multiple: true,
    multipleWidth: 170,
    nonSelectedText: "Select an option",
    minimumCountSelected: 10,
    onOptgroupClick:function(view){
      filter_name = 'member';
      is_optgroup = true;
      filter_value = view.label;
      filter_selected = view.selected;
      setSession(filter_name, is_optgroup, filter_value, filter_selected);
    },
    onClick:function(view){
      filter_name = 'member';
      is_optgroup = false;
      filter_value = view.value;
      filter_selected = view.selected;
      setSession(filter_name, is_optgroup, filter_value, filter_selected);
    },
    onCheckAll:function(){
      filter_name = 'member';
      is_all = true;
      selecteAll(filter_name, is_all);
    },
    onUncheckAll:function(){
      filter_name = 'member';
      is_all = false;
      selecteAll(filter_name, is_all);
    }
  });

  // Filter-部門
  $('select[name="dept[]"]').multipleSelect({
      multiple: true,
      multipleWidth: 170,
      nonSelectedText: "Select an option",
      minimumCountSelected: 10,
      onClick: function(view){
        filter_name = 'dept';
        is_optgroup = false;
        filter_value = view.value;
        filter_selected = view.selected;
        setSession(filter_name, is_optgroup, filter_value, filter_selected);
      },
      onCheckAll:function(){
        filter_name = 'dept';
        is_all = true;
        selecteAll(filter_name, is_all);
      },
      onUncheckAll:function(){
        filter_name = 'dept';
        is_all = false;
        selecteAll(filter_name, is_all);
      }
  });
  
  //Filter-狀態
  $('select[name="state[]"]').multipleSelect({
    multiple: true,
    multipleWidth: 170,
    nonSelectedText: "Select an option",
    minimumCountSelected: 10,
    onClick:function(view){
      filter_name = 'state';
      is_optgroup = false;
      filter_value = view.value;
      filter_selected = view.selected;
      setSession(filter_name, is_optgroup, filter_value, filter_selected);
    },
    onCheckAll:function(){
        filter_name = 'state';
        is_all = true;
        selecteAll(filter_name, is_all);
    },
    onUncheckAll:function(){
        filter_name = 'state';
        is_all = false;
        selecteAll(filter_name, is_all);
    }
  });

//=====filter勾選後使用ajax取資料=====
function setSession(filter_name, is_optgroup, filter_value, filter_selected){
    console.log(filter_name, is_optgroup, filter_value, filter_selected);
    
    $.ajax({
        url: 'filter/ajaxdata.php',
        method:'POST',
        dataType: 'json',
        data: { //傳送資料
            class: filter_name, 
            is_optgroup: is_optgroup,
            value: filter_value,
            selected: filter_selected
          },
        success: res =>{
            console.log(res)
            taskData = res;
        },
        error: err =>{
            console.log(err)
        },
    }).then(function(){
        gantt.clearAll();
        gantt.updateCollection("staff", taskData.staff);
        gantt.updateCollection("dept", taskData.dept);
        // 获取data中的text属性
        const text = taskData.data[0].text;
        if (text == '查無資料') {
            document.getElementById('no-result').innerText = 'No result found';
        }else{
            document.getElementById('no-result').innerText = '';
            gantt.parse(taskData);
        }
    });
}

function selecteAll(filter_name, is_all){
    console.log("組名:" + filter_name + ", 選擇" + is_all);

    $.ajax({
        url: 'filter/ajaxdata_all.php',
        method:'POST',
        dataType: 'json',
        data: { //傳送資料
            class: filter_name, 
            is_all: is_all
          },
        success: res =>{
            console.log(res)
            taskData = res;
        },
        error: err =>{
            console.log(err)
        },
    }).then(function(){
        gantt.clearAll();
        gantt.updateCollection("staff", taskData.staff);
        gantt.updateCollection("dept", taskData.dept);
        // 获取data中的text属性
        const text = taskData.data[0].text;
        if (text == '查無資料') {
            document.getElementById('no-result').innerText = 'No result found';
        }else{
            document.getElementById('no-result').innerText = '';
            gantt.parse(taskData);
        }
    });
}


// Timeline 旁邊的備註文字
gantt.templates.rightside_text = function (start, end, task) {
    if (task.type == gantt.config.types.milestone) {
        return task.text;
    }
    return "";
};


gantt.config.types.root = "project-task";

// set initial values based on task type
function defaultValues(task) {
    var text = "",
        index = gantt.getChildren(task.parent || gantt.config.root_id).length + 1,
        types = gantt.config.types;

    switch (task.type) {
        case types.project:
            text = "Project";
            break;
    }
    task.text = text + " #" + index;
    return;
}

//在新增task或milestone時禁止選擇project選項
function disabledProjectSelect(){
    // 获取所有具有class="gantt_cal_ltext"的元素
    var elementsWithClass = document.querySelectorAll('.gantt_cal_ltext');

    // 获取第二个具有class="gantt_cal_ltext"的元素（索引1表示第二个元素）
    var secondElementWithClass = elementsWithClass[1];

    // 在第二个元素内部获取select元素
    var selectElement = secondElementWithClass.querySelector('select');

    //如果上一個是打開編輯的lightbox的話，整個type的下拉選單會被設成disabled，所以在這裡要先把disabled解掉
    selectElement.disabled = false;  

    // 获取“Project”选项
    var projectOption = selectElement.querySelector('option[value="project"]');

    projectOption.disabled = true;

    console.log(projectOption);

}


gantt.attachEvent("onLightbox", function (task_id){

    console.log("on Lightbox~"+task_id);
    let idTitle = task_id.slice(0, 1);
        switch(idTitle){
            case 't':
                DisableTypeOnUpdate();
                break;

            case 'm':
                DisableTypeOnUpdate();
                break;

            default:
                break;
        }
});

gantt.attachEvent("onLightboxChange", function(old_type, new_type){
    console.log("old Type = " + old_type);
    console.log("new Type = " + new_type);
    disabledProjectSelect();
});

//在編輯時禁止更改類型
function DisableTypeOnUpdate(){  
    var elementsWithClass = document.querySelectorAll('.gantt_cal_ltext');
    // 获取第二个具有class="gantt_cal_ltext"的元素（索引1表示第二个元素）
    var secondElementWithClass = elementsWithClass[1];
    // 在第二个元素内部获取select元素
    var selectElement = secondElementWithClass.querySelector('select');
    console.log(selectElement);
    selectElement.disabled = true;
}


//pop-up新增資料->點擊"SAVE"後觸發，在此將資料傳送至後端
gantt.attachEvent("onAfterTaskAdd", function (id, item) {
    let gantt_id = item.id;
    let taskDesc = item.text;
    let taskType = item.type;
    let taskOwner = null;
    let taskDuration = null;
    let taskStartDay = item.start_date;
    let taskEndDay = item.end_date;
    let taskStatus = item.task_status;
    console.log(taskType);

    console.log('###onAfterTaskAdd###')
    console.log(addButtonStatus);
    console.log(item)
    console.log(taskStatus);

    //將資料儲存
    switch (addButtonStatus) {
        case 'addP':        //點擊的是新增專案的 "+"
            if (taskType == "project") {
                console.log('add a project!!!')

                //打包資料準備傳送給後端
                let keys =
                {
                    'gantt_id': gantt_id,
                    'pid': item.parent,
                    'desc': taskDesc,
                    'type': taskType,
                    'dept': item.dept_id,
                    'owner': taskOwner,
                    'duration': taskDuration,
                    'start_date': taskStartDay,
                    'end_date': taskEndDay,
                    'status': taskStatus
                }

                fetch('gantt_popup/gantt_Create.php', {
                    //對php發送POST請求
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'  //設定傳送內容為json
                    },
                    body: JSON.stringify(keys)             //傳送內容
                })
                .catch(error => {
                    console.error('發生錯誤:', error);
                });

                // 新增完後把新加入的project加入session
                console.log("Next --> after create");
                location.href = "gantt_popup/afterCreate.php";
                // $.ajax({
                //     url: 'gantt_popup/afterCreate.php',
                //     async: false,
                //     // method:'POST',
                //     // dataType: 'json',
                //     success: res =>{
                //         console.log(res)
                //     },
                //     error: err =>{
                //         console.log(err)
                //     },
                // });
            }
            else {
                alert("Can't not add Task(or Milestone) here")
                location.href = "welcome.php";
            }
            break;

        default:        //點擊的是任務後面的 "+"
            if (taskType == "task") 
            {
                console.log("add a task")
                taskOwner = item.owner_id;
                taskDuration = item.duration;
            } 
            else if (taskType == "milestone") 
            {
                console.log("add a milestone")
            }
            else
            {
                alert("Can't not add Project here")
                location.href = "welcome.php";
            }
            //打包資料準備傳送給後端
            let keys =
            {
                'gantt_id': gantt_id,
                'parent': item.parent,
                'desc': taskDesc,
                'type': taskType,
                'owner': taskOwner,
                'duration': taskDuration,
                'start_date': taskStartDay,
                'end_date': taskEndDay,
                'status': taskStatus
            }

            fetch('gantt_popup/gantt_Create.php', {
                //對php發送POST請求
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'  //設定傳送內容為json
                },
                body: JSON.stringify(keys)             //傳送內容
                
            })
                .catch(error => {
                    console.error('發生錯誤:', error);
                });   
            // window.location.href='welcome.php';
            
            break;
    }

});

gantt.attachEvent("onBeforeTaskMove", function(id, parent, tindex){
    console.log("###onBeforeTaskMove###")
    var task = gantt.getTask(id);
    if(task.parent != parent){
        console.log("Can't Move here");
        return false;
    }
    return true;
});


//在編輯的Lightbox中按下Save時先判斷是否真的有有修改資料，有的話才觸發onAfterTaskUpdate
gantt.attachEvent("onLightboxSave", function(id, task, is_new) {
    console.log("###onLightboxSave###");
    //新增任務
    if(is_new == 1){  
        return true;
    }

    //編輯任務
    if(is_new == 0){  
        //先判斷目前抓的id是gantt_id還是資料庫儲存的id
        var db_id;
        console.log(typeof id);
        if(typeof id == "number")  //是gantt_id(代表是剛新增完的，還沒刷新頁面)
        {
            db_id = getTaskid(id); //去取得存在資料庫的id
            db_id = db_id.trim(); //去除id前面的空格
        }else{                    //是資料庫的id
            db_id = id;
        }

        //判斷有沒有改動時間->若有就要到回傳"true",進到onAfterTaskUpdate裡面更新
        //獲取更新前時間
        var originalTask = gantt.getTask(id);
        var originalStartDate = originalTask.start_date.toISOString();
        var originalEndDate = originalTask.end_date.toISOString();
        
        //獲取更新後時間
        var editedStartDate = task.start_date.toISOString();
        var editedEndDate = task.end_date.toISOString();
    
        console.log("Start更新前=" + originalStartDate + ", Start更新後=" + editedStartDate);
        console.log("End更新前=" + originalEndDate + ", End更新後=" + editedEndDate);

        //準備更新後的資料
        let self_id = db_id;
        let taskDesc = task.text;
        let taskType = task.type;
        let taskOwner = null;
        let projectDepartment = null;
        let taskDuration = null;
        let taskStartDay = task.start_date;
        let taskEndDay = task.end_date;
        let taskStatus = task.task_status;


        var type = self_id.slice(0, 1)
        console.log("type="+type);
        if(type == "t")  //task
        {
            taskOwner = task.owner_id;
            taskDuration = task.duration;
            // 檢查是否有更改時間
            if ( editedStartDate !== originalStartDate  || editedEndDate != originalEndDate) 
            {
                // alert("有改task時間");
                return true;  
            } 
            else 
            {
                // alert("沒有改task時間");
                //打包資料準備傳送給後端
                let data = 
                {
                    'id': self_id,
                    'parent': task.parent,
                    'text': taskDesc,
                    'type': taskType,
                    'owner': taskOwner,
                    'department': projectDepartment,
                    'duration': taskDuration,
                    'start_day': taskStartDay,
                    'end_date': taskEndDay,
                    'status': taskStatus
                }
                UpdateTask(data);
                return false;
            }
        }
        else if(type == 'm') //milestone
        {
            // 檢查是否有更改時間
            if (editedStartDate !== originalStartDate )
            {
                // alert("有改nilestone時間");
                return true;
            } 
            else 
            {
                // alert("沒有改milestone時間");
                //打包資料準備傳送給後端
                let data = 
                {
                    'id': self_id,
                    'parent': task.parent,
                    'text': taskDesc,
                    'type': taskType,
                    'owner': taskOwner,
                    'department': projectDepartment,
                    'duration': taskDuration,
                    'start_day': taskStartDay,
                    'end_date': taskEndDay,
                    'status': taskStatus
                }
                UpdateTask(data);
                return false;
            }
        }
        else //Project
        {
            projectDepartment = task.dept_id;
            let data = 
            {
                'id': self_id,
                'parent': task.parent,
                'text': taskDesc,
                'type': taskType,
                'owner': taskOwner,
                'department': projectDepartment,
                'duration': taskDuration,
                'start_day': taskStartDay,
                'end_date': taskEndDay,
                'status': taskStatus
            }
            UpdateTask(data);
            return false;
        }
    }
});

gantt.attachEvent("onBeforeTaskUpdate", function(id, new_item){
    console.log("###onBeforeTaskUpdate###");
    console.log(id, new_item.status);
    if(new_item.status != null){
        return false;
    }
    else{
        return true;
    }
});

//pop-up編輯資料->點擊"SAVE"後觸發，在此將資料傳送至後端
gantt.attachEvent("onAfterTaskUpdate", function(id,item){
    console.log("###onAfterTaskUpdate###");

    // 獲取modal視窗超連結元素，並且模擬點擊超連結以跳出異動視窗
    var linkElement = document.querySelector(".modaal");
    linkElement.click(); 

    var selectedValue = null; //儲存理由的選項值
    var ReasonText = null; //如果理由選擇"其他"，就將使用者輸入文字儲存在這裡

    //確認理由後更新
    var checkReasonButton = document.querySelector("#confirm-reason");
    checkReasonButton.addEventListener("click", function(){
        selectedValue = $("input[name='flexRadioDefault']:checked").val();
        switch(selectedValue){
            case "1":
                ReasonText = "PM部分排程變更";
                break;
            case "2":
                ReasonText = "開案時間延期";
                break;
            case "3":
                ReasonText = "因有急件須優先處理";
                break;
            case "4":
                ReasonText = "未收到執行所需資料";
                break;
            case "5":
                ReasonText = document.querySelector('input[name="otherReason"].input-text').value;
                if (ReasonText.trim() === "") {
                    alert("请輸入其他原因");
                    return;
                }
                break;
            default:
                console.log("switch配對失敗");
                break;
        }

        let self_id = item.id;
        let taskDesc = item.text;
        let taskType = item.type;
        let taskOwner = null;
        let projectDepartment = null;
        let taskDuration = null;
        let taskStartDay = item.start_date;
        let taskEndDay = item.end_date;
        let taskStatus = item.task_status;

        console.log('onAfterTaskUpdate')
        console.log(item)
    
        if(taskType == "project")
        {
            console.log("Update a project");
            projectDepartment = item.dept_id;
    
        }
        else if (taskType == "task")
        {
            console.log("Update a task");
            taskOwner = item.owner_id;
            taskDuration = item.duration;
        }
        else
        {
            taskStartDay = item.start_date;
            console.log("Update a milestone");
        }
        
        //打包資料準備傳送給後端
        let keys = 
        {
            'id': self_id,
            'parent': item.parent,
            'text': taskDesc,
            'type': taskType,
            'owner': taskOwner,
            'department': projectDepartment,
            'duration': taskDuration,
            'start_day': taskStartDay,
            'end_date': taskEndDay,
            'update_reason': ReasonText,
            'status': taskStatus
        }

        console.log(
            self_id, item.parent, taskDesc, taskType, taskOwner, projectDepartment, taskStartDay, taskDuration, taskEndDay, ReasonText, taskStatus
            )

        fetch('gantt_popup/gantt_Update.php', {  
            //對php發送POST請求
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'  //設定傳送內容為json
            },
            body: JSON.stringify(keys)             //傳送內容
        })
            .catch(error => {
            console.error('發生錯誤:', error);
        });
        location.href = "welcome.php";

    });

    //取消更新
    var checkReasonButton = document.querySelector("#cancel-reason");
    checkReasonButton.addEventListener("click", function(){
        console.log("取消更新");
        // location.href = "welcome.php";
        var closeButton = document.querySelector("#modaal-close");
        closeButton.click();
    }); 

});

//pop-up刪除資料->點擊"SAVE"後觸發，在此將資料傳送至後端
gantt.attachEvent("onAfterTaskDelete", function (id,item){
    console.log("onAfterDelete");
    console.log(id);
    console.log(item);

    let keys = 
    {
        'id': id,
        'type': item.type,
        'parent': item.parent
    }
    console.log(id,item.type,item.parent);
    
    fetch('gantt_popup/gantt_Delete.php', {  
        //對php發送POST請求
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'  //設定傳送內容為json
        },
        body: JSON.stringify(keys)             //傳送內容
    }).catch(error => {
        console.error('發生錯誤:', error);
    });
});

gantt.attachEvent("onTaskCreated", function (task) {
    console.log("###onTaskCreated###");

    // 檢查status是否已經存在，如果不存在，則創建它
    if (!task.hasOwnProperty('status')) {
        task.status = "1";
    }
    console.log(task)
    // if (checkTaskTypes) {

    //     let newId = id.slice(0, 1);
    //     console.log(newId);
    //     switch (newId) {
    //         case 't':
    //             gantt.locale.labels.section_description = "Task Title"; // 更改 Descript title 名稱
    //             break;
    //         case 'm':
    //             gantt.locale.labels.section_description = "Milestone Title"; // 更改 Descript title 名稱
    //             break;
    //         default:
    //             gantt.locale.labels.section_description = "Title"; // 更改 Descript title 名稱
    //             break;
    //     }

    // } else {
    //     gantt.locale.labels.section_description = "Project Title"; // 更改 Descript title 名稱

    // }
    
    // gantt.locale.labels.section_description = "Project Title"; // 更改 Descript title 名稱

    var parent = task.parent,
        types = gantt.config.types,
        level = 0;
    
    if (parent == gantt.config.root_id || !parent) {
        level = 0;
    } else {
        level = gantt.getTask(task.parent).$level + 1;
    }
    //assign task type based on task level
    switch (level) {
        case 0:
            task.type = types.project;
            break;
    }

    defaultValues(task);
    return true;
});


gantt.attachEvent("onBeforeLightbox", function(id){
    var lightbox = gantt.getLightbox();
    // lightbox.getInput("type").querySelector('option[value="project"]').disabled = true;
    return true;
})



//當任務上下拖動時
gantt.attachEvent("onRowDragEnd", function(id, target, order) {  //target是父項目的id
    console.log("### onRowDragEnd ###");
    console.log(id, target, order);

    if(target != 0) //target=0代表移動的是專案，不要進行更新
    {
        console.log("Update ROw!!")
        $.ajax({
            url: "lib/UpdateOrder.php",
            type: "POST",
            data: { id: id,
                    parent: target,
                    order: order
            },
            success: function(response) {
                // 在此處處理PHP返回的資料
                console.log("Switch to DB");
                res = response;
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    
    
        $.ajax({
            url: 'show_board/gantt_getdata.php',
            method:'GET',
            dataType: 'json',
            success: res =>{
                taskdata = res;
                console.log(taskdata.data);
            },
            error: err =>{
                console.log(err)
            },
        }).then(function(){
            // const parentData = [];
            for (var i=0 ; i <59  ; i++) {
                if (taskdata.data[i].parent == target) {
                    console.log(taskdata.data[i].text );
                }
              }
        });    
    }
});


gantt.templates.grid_row_class =
    gantt.templates.task_row_class =
    gantt.templates.task_class = function (start, end, task) {
        switch (task.type) {
			case gantt.config.types.project:
				return 'project-task';
				break;
		}

        var css = [];
        if (task.$virtual || task.type == gantt.config.types.project)
            css.push("summary-bar");

        if (task.owner_id) {
            css.push("gantt_resource_task gantt_resource_" + task.owner_id);
        }

        return css.join(" ");
    };


gantt.plugins({
    marker: true,
    multiselect: true,
    click_drag: true,
    tooltip: true  //開啟Hover效果
});

gantt.config.order_branch = "marker";  //啟動拖曳移動
gantt.config.work_time = true;  // removes non-working time from calculations
gantt.config.skip_off_time = true;    // hides non-working time in the chart


gantt.setWorkTime({ day:3, hours:false });

gantt.config.click_drag = {
    callback: onDragEnd
};

gantt.config.autoscroll = true;  //在時間軸上"水平"或"垂直"拖曳項目時會自動捲動時間軸 
gantt.config.autoscroll_speed = 50;
gantt.config.fit_tasks = true; //甘特圖自動適應時間範圍

gantt.templates.task_class = function (st, end, item) {
    return item.$level == 0 ? "gantt_projectbox" : ""
};

gantt.templates.scale_cell_class = function (date) {
    if (date.getDay() == 0 || date.getDay() == 6) {
        return "weekend";
    }
};
gantt.templates.timeline_cell_class = function (task, date) {
    if (date.getDay() == 0 || date.getDay() == 6) {
        return "weekend";
    }
};
    
//定位tooltip位置在滑鼠旁邊
gantt.attachEvent("onGanttReady", function(){
    var tooltips = gantt.ext.tooltips;
    tooltips.tooltip.setViewport(gantt.$task_data);
});

gantt.config.show_progress = false;

//===================圖表顯示Scale設置===================//

var zoomConfig = {
    levels: [
      {
        name:"day",
        scale_height: 70,
        min_column_width:35,
        scales:[
            { unit: "month", step: 1, date: "%F, %Y" },
            { unit: "day", step: 1, date: "%D" },
            { unit: "day", step: 1, date: "%d" }
        ]
      },
      {
        name:"week",
        scale_height: 70,
        min_column_width:50,
        scales:[
          {unit: "week", step: 1, format: function (date) {
            var year = gantt.date.date_to_str("%Y")(date);
            // var dateToStr = gantt.date.date_to_str("%d %M");
            // var endDate = gantt.date.add(date, -6, "day");
            var weekNum = gantt.date.date_to_str("%W")(date);
            return "Week " + weekNum + ", " + year;
          }},
          {unit: "day", step: 1, format: "%j %D"}
        ]
      },
      {
        name:"month",
        scale_height: 70,
        min_column_width:220,
        scales:[
          {unit: "month", format: "%F, %Y"}
        ]
      },
      {
        name:"quarter",
        height: 50,
        min_column_width:90,
        scales:[
          {unit: "month", step: 1, format: "%M"},
          {
            unit: "quarter", step: 1, format: function (date) {
              var dateToStr = gantt.date.date_to_str("%M");
              var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
              return dateToStr(date) + " - " + dateToStr(endDate);
            }
          }
        ]
      },
      {
        name:"year",
        scale_height: 50,
        min_column_width: 30,
        scales:[
          {unit: "year", step: 1, format: "%Y"}
        ]
      }
    ]
  };

  gantt.ext.zoom.init(zoomConfig);  //載入設置
  gantt.ext.zoom.setLevel("day");  //預設為day


  gantt.templates.task_class = function (start, end, task) {
    let className = "";

    if (task.type != 'project') {
        switch (task.task_status) {
            case '1':
                className = " task-Inprogress";
                break;
            case '2':
                className = " task-Maintain";
                break;
            case '3':
                className = " task-Pending";
                break;
            case '4':
                className = " task-Complete";
                break;
            case '5':
                className = " task-Urgent";
                break;
        }

        return className;

    } else if (task.type == 'project') {
        switch (task.status) {
            case '1':
                className += " project-Inprogress";
                break;
            case '2':
                className += " project-Maintain";
                break;
            case '3':
                className += " project-Pending";
                break;
            case '4':
                className += " project-Complete";
                break;
            case '5':
                className += " project-Urgent";
                break;
        }

        return className;
    }
};



//==========設定gantt卷軸範圍==========//
// 獲取今天的日期
var todayDate = new Date();
// 計算一年前/後的日期
var oneYearAgo = new Date();
var oneYearLater = new Date();
oneYearAgo.setFullYear(todayDate.getFullYear() - 1);
oneYearLater.setFullYear(todayDate.getFullYear() + 1);
//設定卷軸的start_date和end_date
gantt.config.start_date = oneYearAgo;
gantt.config.end_date = oneYearLater


//==================================== 初始化甘特圖 ====================================//
gantt.init("gantt_here");                                //===========================//
// gantt.enablePreRendering(50);  //預先渲染                 //===========================//
gantt.showDate(new Date());                              //===========================//
//====================================================================================//

gantt.attachEvent("onTaskLoading", function (task) { // Task 載入時就預設關閉全部的專案
    task.$open = false;
    return true;
});

gantt.attachEvent("onGanttScroll", function (left, top) {
    // console.log("###onGanttScroll###");
    const left_date = gantt.dateFromPos(left)
    const right_date = gantt.dateFromPos(left + gantt.$task.offsetWidth)

    gantt.config.start_date = gantt.config.start_date || gantt.getState().min_date;
    gantt.config.end_date = gantt.config.end_date || gantt.getState().max_date;

    const min_allowed_date = gantt.date.add(gantt.config.start_date, 1, "day");
    const max_allowed_date = gantt.date.add(gantt.config.end_date, -6, "day");

    let repaint = false;
    if (+left_date <= +min_allowed_date) {
        if(gantt.getState().scale_unit == "month")  //如果view是切到"月"，start_date就減多一點(大於1個月)，不然這個if條件句會一直成立，造成圖表無限往前擴增
            gantt.config.start_date = gantt.date.add(gantt.config.start_date, -35, "day");
        else
            gantt.config.start_date = gantt.date.add(gantt.config.start_date, -6, "day");

        repaint = true;
    }
    if (+right_date >= +max_allowed_date) {
        gantt.config.end_date = gantt.date.add(gantt.config.end_date, 6, "day");
        repaint = true;
    }


    //setTimeout(functionRef, delay)
    if (repaint) {
        setTimeout(() => {
            gantt.render()
            gantt.showDate(left_date)
        },20)
    }
});

//===============標註今日時間線===============//
let today = new Date();
let year = today.getFullYear();
let month = today.getMonth();
let day = today.getDate();

console.log(year, month, day)

gantt.attachEvent("onGanttRender", onGanttRender_todayLine(new Date(year, month, day)));
//onGanttRender_todayLine(new Date(year, month, day))


//usage

function onGanttRender_todayLine(today) {
    console.log("***onGanttRender_todayLine***");
    function addDays(date, days) {
        var result = new Date(date);
        result.setDate(date.getDate() + days);
        return result;
    }
    return function f() {
        var $today = $("#today");
        if (!$today.length) {
            var elem = document.createElement("div");
            elem.id = "today";
            gantt.$task_data.appendChild(elem);
            $today = $(elem);
        }
        var x_start = gantt.posFromDate(today);
        var x_end = gantt.posFromDate(addDays(today, 1));
        $today.css("left", Math.floor(x_start + 0.5 * (x_end - x_start)) + "px");
    };
}


function UpdateTask(data){  //沒有更新時時間的更新
    console.log("***UpdateTask***");
    console.log(data);
    fetch('gantt_popup/gantt_Update.php', {  
        //對php發送POST請求
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'  //設定傳送內容為json
        },
        body: JSON.stringify(data)             //傳送內容
    })
        .catch(error => {
        console.error('發生錯誤:', error);
    });
    location.href = "welcome.php";
}

//用(前端的)gantt_id去資料庫換得(後端的)task_id
function getTaskid(gantt_id){
    console.log("***getTaskid***")
    var type = gantt.getLightboxSection('type').getValue();
    console.log("This type of box is : "+ type);

    $.ajax({
        type: "POST",
        url: "gantt_popup/getTaskid.php", // 將此替換為處理資料的PHP檔案路徑
        async: false, //設置為同步請求
        data: { id: gantt_id,
                type: type
        }, // 傳遞項目的id給後端
        success: function(response) {
          // 在此處處理PHP返回的資料
          console.log("Switch to DB id = " + response);
          res = response;
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    return res;
}


function unselectTasks() {
    gantt.eachSelectedTask(function (item) {
        gantt.unselectTask(item.id);
    });
};



function onDragEnd(startPoint, endPoint, startDate, endDate, tasksBetweenDates, tasksInRows) {
    console.log("###On Drag End###");
    var mode = document.querySelector("input[name=selectMode]:checked").value;
    switch (mode) {
        case "1":
            unselectTasks();
            tasksBetweenDates.forEach(function (item) {
                gantt.selectTask(item.id);
            });
            break;
        case "2":
            unselectTasks();
            tasksInRows.forEach(function (item) {
                gantt.selectTask(item.id);
            });
            break;
        case "3":
            unselectTasks();
            for (var i = 0; i < tasksBetweenDates.length; i++) {
                for (var j = 0; j < tasksInRows.length; j++) {
                    if (tasksBetweenDates[i] === tasksInRows[j]) {
                        gantt.selectTask(tasksBetweenDates[i].id);
                    }
                }
            }
            break;
            return;
    }
}


function sendDataHandeer(task){
    let taskId = null;
    console.log("This is in function")
    console.log(task.parent);
    keys = task.id;
  }