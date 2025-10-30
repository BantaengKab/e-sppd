# Universal Modal CRUD System - Usage Guide

The enhanced `loadModalContent` function now supports all CRUD operations with a universal, reusable interface. This system provides consistent modal operations across your entire application.

## 🔧 **Core Functions**

### **1. loadModalContent(modalId, url, options)**
The universal function that powers all CRUD operations.

**Parameters:**
- `modalId` (string): The ID of the modal element
- `url` (string): The URL to fetch content from
- `options` (object): Optional configuration

**Options:**
```javascript
{
    title: 'Custom Modal Title',           // Sets modal title
    size: 'lg',                           // Modal size: sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, full
    loadingText: 'Loading...',           // Custom loading text
    method: 'GET',                      // HTTP method: GET, POST, PUT, DELETE
    data: null,                         // Request data (FormData or JSON)
    onSuccess: (result) => {},            // Success callback
    onError: (error) => {}              // Error callback
}
```

### **2. Universal CRUD Functions**

#### **createRecord(modalId, url, options)**
```javascript
createRecord('user-create-modal', '/users/create', {
    title: 'Tambah Pengguna Baru',
    size: 'lg',
    loadingText: 'Memuat formulir...'
});
```

#### **viewRecord(modalId, url, options)**
```javascript
viewRecord('user-detail-modal', '/users/123', {
    title: 'Detail Pengguna',
    size: 'xl'
});
```

#### **editRecord(modalId, url, options)**
```javascript
editRecord('user-edit-modal', '/users/123/edit', {
    title: 'Edit Pengguna',
    size: 'lg',
    onSuccess: (result) => {
        console.log('Edit modal loaded successfully');
    }
});
```

#### **deleteRecord(url, options)**
```javascript
deleteRecord('/users/123', {
    confirmMessage: 'Are you sure you want to delete this user?',
    successMessage: 'User deleted successfully!',
    onSuccess: (result) => {
        console.log('User deleted');
    }
});
```

#### **bulkDelete(url, selectedIds, options)**
```javascript
const selectedIds = ['1', '2', '3'];
bulkDelete('/users/bulk-delete', selectedIds, {
    confirmMessage: `Delete ${selectedIds.length} users?`,
    successMessage: `${selectedIds.length} users deleted successfully!`
});
```

## 📋 **Usage Examples**

### **Basic CRUD Operations**

```html
<!-- Create Button -->
<button onclick="createRecord('user-create-modal', '/users/create')">
    Add User
</button>

<!-- View Button -->
<button onclick="viewRecord('user-detail-modal', '/users/123')">
    View Details
</button>

<!-- Edit Button -->
<button onclick="editRecord('user-edit-modal', '/users/123/edit')">
    Edit User
</button>

<!-- Delete Button -->
<button onclick="deleteRecord('/users/123')">
    Delete User
</button>
```

### **Advanced Usage with Options**

```html
<!-- Custom Create Modal -->
<button onclick="createRecord('user-create-modal', '/users/create', {
    title: 'Tambah Pengguna Administrator',
    size: '2xl',
    loadingText: 'Memuat formulir administrator...',
    onSuccess: (result) => {
        console.log('Create form loaded');
        // Custom success handling
    }
})">
    Add Admin User
</button>

<!-- Custom Edit Modal with Callback -->
<button onclick="editRecord('user-edit-modal', '/users/123/edit', {
    title: 'Edit Pengguna - {{ user.name }}',
    size: 'lg',
    onSuccess: (result) => {
        // Focus on specific field after loading
        setTimeout(() => {
            document.getElementById('email')?.focus();
        }, 200);
    }
})">
    Edit User
</button>

<!-- Bulk Operations -->
<button onclick="handleBulkDelete()">Delete Selected</button>

<script>
function handleBulkDelete() {
    const selectedIds = getSelectedUserIds();
    if (selectedIds.length === 0) {
        crudModal.showNotification('Please select at least one user', 'warning');
        return;
    }

    bulkDelete('/users/bulk-delete', selectedIds, {
        confirmMessage: `Delete ${selectedIds.length} selected users?`,
        onSuccess: (result) => {
            // Refresh table or update UI
            setTimeout(() => location.reload(), 1000);
        }
    });
}
</script>
```

### **Dynamic Modal Content**

```javascript
// Load custom content
loadModalContent('custom-modal', '/api/custom-data', {
    title: 'Custom Data View',
    size: 'xl',
    method: 'POST',
    data: { filter: 'active' },
    onSuccess: (result) => {
        // Handle loaded content
        initializeCustomFeatures(result.modalId);
    }
});

// AJAX form submission within modal
loadModalContent('form-modal', '/api/submit-form', {
    method: 'POST',
    data: formData,
    onSuccess: (result) => {
        if (result.success) {
            crudModal.showNotification('Form submitted successfully!', 'success');
            setTimeout(() => crudModal.closeModal('form-modal'), 1500);
        }
    }
});
```

## 🎨 **Modal Size Options**

- `sm`: `max-w-md` (Small)
- `md`: `max-w-lg` (Medium - Default)
- `lg`: `max-w-xl` (Large)
- `xl`: `max-w-2xl` (Extra Large)
- `2xl`: `max-w-3xl` (2X Large)
- `3xl`: `max-w-4xl` (3X Large)
- `4xl`: `max-w-5xl` (4X Large)
- `5xl`: `max-w-6xl` (5X Large)
- `6xl`: `max-w-7xl` (6X Large)
- `full`: `max-w-full` (Full Width)

## 🔔 **Error Handling**

The system includes comprehensive error handling:

```javascript
try {
    await editRecord('user-edit-modal', '/users/123/edit');
} catch (error) {
    console.error('Failed to load edit modal:', error);
    // Custom error handling
}
```

## 📱 **Best Practices**

1. **Always include meaningful titles** for better UX
2. **Use appropriate modal sizes** based on content complexity
3. **Provide custom loading text** for better user feedback
4. **Implement success/error callbacks** for custom handling
5. **Use confirm dialogs** for destructive operations
6. **Handle edge cases** in error callbacks

## 🔄 **Migration from Old System**

**Before:**
```javascript
onclick="loadModalContent('user-edit-modal', '/users/123/edit')"
onclick="deleteUser(123)"
```

**After:**
```javascript
onclick="editRecord('user-edit-modal', '/users/123/edit')"
onclick="deleteRecord('/users/123')"
```

The new system maintains backward compatibility while providing enhanced functionality and better organization.