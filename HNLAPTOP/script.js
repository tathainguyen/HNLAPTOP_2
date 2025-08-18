// sell

function startCountdown(durationInSeconds) {
    const daysElem = document.getElementById("days");
    const hoursElem = document.getElementById("hours");
    const minutesElem = document.getElementById("minutes");
    const secondsElem = document.getElementById("seconds");

    // Nếu không có đủ phần tử, không chạy countdown
    if (!daysElem || !hoursElem || !minutesElem || !secondsElem) return;

    let timeRemaining = durationInSeconds;

    function updateCountdown() {
        const days = Math.floor(timeRemaining / (24 * 60 * 60));
        const hours = Math.floor((timeRemaining % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((timeRemaining % (60 * 60)) / 60);
        const seconds = timeRemaining % 60;

        daysElem.textContent = days.toString().padStart(2, "0");
        hoursElem.textContent = hours.toString().padStart(2, "0");
        minutesElem.textContent = minutes.toString().padStart(2, "0");
        secondsElem.textContent = seconds.toString().padStart(2, "0");

        if (timeRemaining > 0) {
            timeRemaining--;
        } else {
            clearInterval(interval);
        }
    }

    updateCountdown();
    const interval = setInterval(updateCountdown, 1000);
}
startCountdown(7 * 24 * 60 * 60);


//quản lý hàng hóa
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ thực hiện nếu các phần tử tồn tại (để tránh lỗi ở các trang khác)
    const searchInput = document.getElementById('searchInput');
    const productsTable = document.getElementById('productsTable');
    
    if (searchInput && productsTable) {
        const rows = productsTable.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();
            
            rows.forEach(function(row) {
                // Bỏ qua các hàng edit (hiển thị khi chỉnh sửa)
                if (row.id.startsWith('editRow_')) return;
                
                const visible = Array.from(row.querySelectorAll('td')).some(cell => {
                    return cell.textContent.toLowerCase().includes(searchTerm);
                });
                
                row.style.display = visible ? '' : 'none';
                
                // Nếu hàng hiển thị được ẩn, cũng ẩn hàng edit tương ứng
                const id = row.id.replace('row_', '');
                const editRow = document.getElementById('editRow_' + id);
                if (editRow) {
                    editRow.style.display = 'none';
                }
            });
        });
    }
});

// Các hàm cho chức năng sửa sản phẩm trong trang quản lý hàng hóa
function enableEdit(id) {
    document.getElementById('row_' + id).style.display = 'none';
    document.getElementById('editRow_' + id).style.display = 'table-row';
}

function cancelEdit(id) {
    document.getElementById('row_' + id).style.display = 'table-row';
    document.getElementById('editRow_' + id).style.display = 'none';
}




//quản lý đơn hàng
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem đang ở trang quản lý đơn hàng không
    const ordersTable = document.querySelector('.animate__animated.animate__fadeIn table');
    
    if (ordersTable) {
       
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    }
});

// Tương tác với Alpine.js 
document.addEventListener('alpine:init', function() {
 
    // Để trống để đảm bảo sự tương thích với Alpine.js
});




// Quản lý liên hệ (admin_contact.php)
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem đang ở trang quản lý liên hệ không bằng cách tìm các phần tử đặc trưng
    const contactRows = document.querySelectorAll('.contact-row');
    
    if (contactRows.length > 0) {
        // Add animations to rows when loaded
        contactRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    }
});

// Confirm delete contact
function confirmDelete(contactId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Liên hệ này sẽ bị xóa vĩnh viễn!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            backdrop: `rgba(0,0,0,0.4)`,
            customClass: {
                container: 'custom-swal-container',
                popup: 'rounded-xl',
                title: 'text-slate-800',
                content: 'text-slate-600'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                deleteContact(contactId);
            }
        });
    } else {
        // Fallback nếu SweetAlert không được tải
        if (confirm('Bạn có chắc chắn muốn xóa liên hệ này?')) {
            deleteContact(contactId);
        }
    }
}

// Delete contact
function deleteContact(contactId) {
    fetch(`delete_contact.php?id=${contactId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Đã xóa!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    // Reload trang sau khi xóa để cập nhật số liệu
                    window.location.reload();
                });
            } else {
                alert('Đã xóa liên hệ thành công!');
                // Reload trang sau khi xóa
                window.location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Lỗi!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            } else {
                alert('Lỗi: ' + data.message);
            }
        }
    })
    .catch(error => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi xóa liên hệ',
                icon: 'error',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert('Có lỗi xảy ra khi xóa liên hệ');
        }
    });
}

// Hàm hiển thị thông báo - được sử dụng chung cho nhiều trang
function showNotification(message, type = "info") {
    // Tạo phần tử thông báo
    const notification = document.createElement("div");
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-opacity duration-300 animate__animated animate__fadeInRight ${type === 'success' ? 'bg-emerald-100 text-emerald-800 border-l-4 border-emerald-500' : 'bg-blue-100 text-blue-800 border-l-4 border-blue-500'}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">${message}</p>
            </div>
        </div>
    `;
    document.body.appendChild(notification);
    
    // Tự động ẩn thông báo sau 3 giây
    setTimeout(() => {
        notification.classList.add("animate__fadeOutRight");
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

