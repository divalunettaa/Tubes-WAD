let users = [];
let currentUserId = null;

document.getElementById("addUserBtn").onclick = function () {
  openModal("Tambah User");
  document.getElementById("userId").readOnly = false; // Reset readOnly
};

document.getElementById("userForm").onsubmit = function (event) {
  event.preventDefault();
  const userId = document.getElementById("userId").value.trim();
  const userName = document.getElementById("userName").value.trim();
  const userEmail = document.getElementById("userEmail").value.trim();

  if (!userId || !userName || !userEmail) {
    alert("Semua kolom harus diisi");
    return;
  }

  if (users.some((user) => user.id === userId) && currentUserId !== userId) {
    alert("ID sudah digunakan, gunakan ID lain.");
    return;
  }

  if (currentUserId) {
    // Update user
    const userIndex = users.findIndex((user) => user.id == currentUserId);
    users[userIndex] = { id: userId, name: userName, email: userEmail };
    currentUserId = null;
  } else {
    // Create new user
    const newUser = { id: userId, name: userName, email: userEmail };
    users.push(newUser);
  }

  closeModal();
  renderTable();
};

function renderTable(data = users) {
  const tbody = document.querySelector("#userTable tbody");
  tbody.innerHTML = "";

  data.forEach((user) => {
    const row = document.createElement("tr");
    row.innerHTML = `  
            <td>${user.id}</td>  
            <td>${user.name}</td>  
            <td>${user.email}</td>  
            <td>  
                <button onclick="editUser('${user.id}')">Edit</button>  
                <button onclick="deleteUser('${user.id}')">Hapus</button>  
            </td>  
        `;
    tbody.appendChild(row);
  });
}

function editUser(userId) {
  const user = users.find((user) => user.id === userId);
  document.getElementById("modalTitle").innerText = "Edit User";
  document.getElementById("userId").value = user.id;
  document.getElementById("userId").readOnly = true;
  document.getElementById("userName").value = user.name;
  document.getElementById("userEmail").value = user.email;
  currentUserId = userId;
  openModal();
}

function deleteUser(userId) {
  const confirmDelete = confirm("Apakah Anda yakin ingin menghapus user ini?");
  if (confirmDelete) {
    users = users.filter((user) => user.id !== userId);
    renderTable();
  }
}

function openModal(title) {
  document.getElementById("modalTitle").innerText = title;
  document.getElementById("userModal").style.display = "block";
}

function closeModal() {
  document.getElementById("userModal").style.display = "none";
  document.getElementById("userForm").reset();
}

// Close modal if user clicks outside of the modal
window.onclick = function (event) {
  const modal = document.getElementById("userModal");
  if (event.target === modal) {
    closeModal();
  }
};

document
  .getElementById("searchBar")
  .addEventListener("input", function (event) {
    const searchTerm = event.target.value.toLowerCase();
    const filteredUsers = users.filter(
      (user) =>
        user.name.toLowerCase().includes(searchTerm) ||
        user.email.toLowerCase().includes(searchTerm)
    );
    renderTable(filteredUsers);
  });
