var taskData = {
	"data": [
	  { "id": 1, "text": "Office itinerancy", "type": "project", "start_date": "02-04-2023 00:00", "duration": 17,"status": 2},
	  { "id": 2, "text": "Office facing", "type": "task", "start_date": "02-04-2023 00:00", "duration": 8, "parent": "1", "owner_id":1},
	  { "id": 3, "text": "Furniture installation", "type": "task", "start_date": "11-04-2023 00:00", "duration": 8, "parent": "1","owner_id":2},
	  { "id": 11, "text": "Product launch", "type": "project", "start_date": "02-04-2023 00:00", "duration": 13, "parent": 0,"status": 1},
	  { "id": 12, "text": "Perform Initial testing", "type": "task", "start_date": "03-04-2023 00:00", "duration": 5, "parent": "11"},
	  { "id": 13, "text": "Development", "type": "task", "start_date": "03-04-2023 00:00", "duration": 11, "parent": "11"},
	  { "id": 14, "text": "Analysis", "type": "task", "start_date": "03-04-2023 00:00", "duration": 6, "parent": "11"},
	  { "id": 15, "text": "Design", "type": "task", "start_date": "03-04-2023 00:00", "duration": 5, "parent": "11"},
	  { "id": 17, "text": "Develop System", "type": "task", "start_date": "03-04-2023 00:00", "duration": 2, "parent": "11"},
	  { "id": 25, "text": "Beta Release", "type": "milestone", "start_date": "06-04-2023 00:00", "parent": "11", "duration": 0},
	  // type: timeline 類型, start_date: 開始時間,parent: 在哪一個 project id 下面,owner_id: 對應到 staff 的 key id, duration: 預估工作時間
	],
	"links": [
	  { "id": "1", "source": "2", "target": "3", "type": "0" },
	  { "id": "2", "source": "17", "target": "25", "type": "0" }
	  // source: 對應到 data 的 id, target 是指連到哪一個 timeline, type 設定0即可，那是不同樣式的意思
	]
	// "member":[
	// 	{
	// 		key: "G5001",
	// 		label: "Kevin"
	// 	},
	// 	{
	// 		key: "G5002",
	// 		label: "Zoe"
	// 	}

	// ]
  }

//   $.getJSON('lib/staff_data.json', function(data){
//     taskData["member"] = data;
//     console.log(taskData);
// 	});

//   gantt.serverList("staff", taskData.member);

//   $.getJSON('lib/staff_data.json', function(data){
//     gantt.serverList("staff", data);
// 	});


/*
  gantt.serverList("staff", [{
		key: 0,
		label: ""
	},
	{
		key: 1,
		label: "John"
	},
	{
		key: 2,
		label: "Mike"
	},
	{
		key: 3,
		label: "Anna"
	},
	{
		key: 4,
		label: "Bill"
	},
	{
		key: 7,
		label: "Floe"
	}
	]);
*/
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