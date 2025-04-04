var rolesData = [
    {
        "id": 1,
        "name": "admin",
        "is_active": true
    }, {
        "id": 2,
        "name": "kitchen",
        "is_active": true
    }, {
        "id": 3,
        "name": "customer",
        "is_active": true
    }
];

var editList = false;

// roles
if (document.getElementById("roles")) {
    var roleList = new gridjs.Grid({
        columns: [
            {
                name: 'Id',
                width: '80px',
                // data: (function (row) {
                //     return gridjs.html('<div class="fw-medium">' + row.id + '</div>');
                // })
                data: (row) => row.id,
                sort: true
            },
            {
                name: 'Name',
                width: '120px',
                sort: true
            },
            {
                name: 'Is Active',
                width: '60px',
                data: (row) => row.is_active ? 'True' : 'False',
                sort: true
            },{
                name: 'Action',
                width: '80px',
                data: (function (row) {
                    return gridjs.html('<ul class="hstack gap-2 list-unstyled mb-0">\
                    <li>\
                        <a href="#" class="badge bg-success-subtle text-success " onClick="editRoleList('+ row.id + ')">Edit</a>\
                    </li>\
                    <li>\
                        <a href="#removeItemModal" data-bs-toggle="modal" class="badge bg-danger-subtle text-danger " onClick="removeItem('+ row.id + ')">Delete</a>\
                    </li>\
                </ul>');
                }),
                sort: false
            },
        ],
        // sort: false,
        pagination: {
            limit: 10
        },
        data: rolesData,
    }).render(document.getElementById("roles"));
};


// Search result list
var searchResultList = document.getElementById("searchResultList");
searchResultList.addEventListener("keyup", function () {
    var inputVal = searchResultList.value.toLowerCase();
    function filterItems(arr, query) {
        return arr.filter(function (el) {
            return el.name.toLowerCase().indexOf(query.toLowerCase()) !== -1
        })
    }

    var filterData = filterItems(rolesData, inputVal);

    roleList.updateConfig({
        data: filterData
    }).forceRender();
});

var createRoleForm = document.querySelectorAll('.createRoleForm')
Array.prototype.slice.call(createRoleForm).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            event.preventDefault();
            var name = document.getElementById('name').value;
            var isActive = document.querySelector('input[name="is_active"]:checked').value;

            if (name !== "" && isActive !== "" && !editList) {
                var newRoleId = findNextId();
                var newRole = {
                    'id': newRoleId,
                    "name": name,
                    "is_active": isActive === "1"
                };

                rolesData.push(newRole);

                roleList.updateConfig({
                    data: rolesData
                }).forceRender();
                clearVal();
                form.classList.remove('was-validated');

            }else if(name !== "" && isActive !== "" && editList){
                var getEditid = document.getElementById("roleid-input").value;

                rolesData = rolesData.map(function (item) {
                    if (item.id == getEditid) {
                        var editObj = {
                            'id': getEditid,
                            "name": name,
                            "is_active": isActive === "1"
                        }
                        return editObj;
                    }
                    return item;
                });

                roleList.updateConfig({
                    data: rolesData
                }).forceRender();
                clearVal();
                form.classList.remove('was-validated');
                editList = false;
            } else {
                form.classList.add('was-validated');
            }
            sortElementsById();
        }
    }, false)
});

function fetchIdFromObj(role) {
    return parseInt(role.id);
}

function findNextId() {
    if (rolesData.length === 0) {
        return 0;
    }
    var lastElementId = fetchIdFromObj(rolesData[rolesData.length - 1]),
        firstElementId = fetchIdFromObj(rolesData[0]);
    return (firstElementId >= lastElementId) ? (firstElementId + 1) : (lastElementId + 1);
}


function sortElementsById() {
    var roles = rolesData.sort(function (a, b) {
        var x = fetchIdFromObj(a);
        var y = fetchIdFromObj(b);

        if (x > y) {
            return -1;
        }
        if (x < y) {
            return 1;
        }
        return 0;
    })
}

sortElementsById();


function editRoleList(elem){
    var getEditid = elem;
    rolesData = rolesData.map(function (item) {
        if (item.id == getEditid) {
            editList = true;
            document.getElementById("addRoleLabel").innerHTML = "Edit Role";
            document.getElementById("addNewRole").innerHTML = "Save";
            document.getElementById("roleid-input").value = item.id;
            document.getElementById("name").value = item.name;

            // Set radio button based on item.isActive value
            if (item.is_active === true) {
                document.getElementById("is_active_1").checked = true;
            } else if (item.is_active === false) {
                document.getElementById("is_active_0").checked = true;
            }
        }
        return item;
    });
}

// removeItem event
function removeItem(elem) {
    var getid = elem;
    document.getElementById("remove-role").addEventListener("click", function () {
        function arrayRemove(arr, value) {
            return arr.filter(function (ele) {
                return ele.id != value;
            });
        }
        var filtered = arrayRemove(rolesData, getid);

        rolesData = filtered;
        roleList.updateConfig({
            data: rolesData
        }).forceRender();

        document.getElementById("closeRemoveRoleModal").click();
    });
}


function clearVal() {
    document.getElementById("addRoleLabel").innerHTML = "Create Role";
    document.getElementById("addNewRole").innerHTML = "Add Role";
    document.getElementById('name').value = "";
    document.getElementById("is_active_1").checked = true;
}
