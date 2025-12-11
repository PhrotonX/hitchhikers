// Drag-and-drop for destination-list
document.addEventListener('DOMContentLoaded', function() {
    const destinationList = document.getElementById('destination-list');
    function updateOrderFields() {
        const items = destinationList.querySelectorAll('.destination-item');
        items.forEach((item, idx) => {
            const orderInput = item.querySelector('input[name="order[]"]');
            if (orderInput) orderInput.value = idx;
        });
    }
    let draggedItem = null;
    destinationList.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('destination-item')) {
            draggedItem = e.target;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', '');
            setTimeout(() => draggedItem.classList.add('dragging'), 0);
        }
    });
    destinationList.addEventListener('dragend', function(e) {
        if (draggedItem) draggedItem.classList.remove('dragging');
        draggedItem = null;
    });
    destinationList.addEventListener('dragover', function(e) {
        e.preventDefault();
        const afterElement = getDragAfterElement(destinationList, e.clientY);
        if (draggedItem) {
            if (afterElement == null) {
                destinationList.appendChild(draggedItem);
            } else {
                destinationList.insertBefore(draggedItem, afterElement);
            }
        }
    });
    destinationList.addEventListener('drop', function(e) {
        e.preventDefault();
        updateOrderFields();
    });
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.destination-item:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: -Infinity }).element;
    }
    // Make destination items draggable when added
    const observer = new MutationObserver(() => {
        destinationList.querySelectorAll('.destination-item').forEach(item => {
            item.setAttribute('draggable', 'true');
        });
    });
    observer.observe(destinationList, { childList: true });
});