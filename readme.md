# Feature Test Assignment

## 1. Instructions

- Clone or fork this repository.
- Create a new branch: `{user}-assignment`.
- Invite **@ikhsan017** and **@dhiaaziz** as collaborators.
- Follow the setup instructions provided in the repository before running the project.

## 2. Feature Requirements

### Core Features (Main Criteria)

- [x] CRUD Suppliers
- [x] CRUD CLT Layups (nested under Supplier)
- [x] CRUD CLT Layers (nested under Layup)

The structure should properly reflect the hierarchy:
Supplier → Layups → Layers

### Data Model (ERD)

Below is the Entity Relationship Diagram (ERD) representing the data structure:

![ERD](./erd-new.png)

### Import / Export (Main Criteria)

- [x] **Export by Supplier**
    - Must include: Supplier + all related Layups + all related Layers

- [x] **Import by Supplier**
    - Must create and/or update Layups and Layers under the specified supplier

Format is flexible (JSON / CSV / Excel, etc.). JSON format is completely acceptable.

## 3. Feature: Conflict Resolution (Bonus – Important)

During import, conflicts may occur when incoming data differs from existing records.

### Conflict Detection Rules

#### 1. Layup-Level Conflict

If a layup with the same `name` already exists under the same supplier:

- Treat it as the same layup candidate.
- Do **not** automatically create a new layup.

#### 2. Layer-Level Conflict

If:

- A layer with the same `layer_order` exists within that layup,
- **AND** one or more fields differ (`thickness`, `width`, `angle`),

→ This must be treated as a conflict.

---

### Required Conflict Handling

You must implement a clearly defined conflict resolution strategy.

At minimum, support **one** of the following:

- **Overwrite Existing**  
  (Incoming data replaces current data)

- **Skip Conflict**  
  (Keep current data, ignore incoming change)

- **Duplicate Layup**  
  (Create a new layup with a suffix such as `name (imported)`)

- **Reject Entire Import**  
  (Abort and return a detailed conflict report)

---

### Advanced Conflict Resolution (UI-Based – Bonus)

For additional bonus points, implement a **manual conflict resolution interface** similar to GitHub merge conflict resolution.

Expected behavior:

- Display **Existing Version (Current Data)** and  
  **Incoming Version (Imported Data)** side-by-side
- Highlight field-level differences
- Allow the user to choose:
    - ✅ Keep Existing
    - ✅ Accept Incoming
- Support resolving conflicts one-by-one
- Provide navigation (e.g., “1 of 3 discrepancies”)

This may be implemented as:

- A modal, or
- A dedicated conflict resolution page.

## Implementation Details

### Features Implemented

#### 1. CRUD Operations

All CRUD operations have been fully implemented:

**Suppliers**
- List all suppliers with pagination
- Create new supplier
- Edit supplier information
- Delete supplier (cascades to delete all related layups and layers)
- View supplier details with all related layups

**Layups (nested under Supplier)**
- List all layups for a specific supplier
- Create new layup with unique name per supplier
- Edit layup information
- Delete layup (cascades to delete all related layers)
- View layup details with all related layers

**Layers (nested under Layup)**
- List all layers for a specific layup sorted by order
- Create new layer with order, thickness, width, and angle
- Edit layer properties
- Delete layer
- View detailed layer information

#### 2. Export Functionality

**Export by Supplier**
- Navigate to Suppliers → Select Supplier → Click Export
- Exports supplier data in JSON format including:
  - Supplier information (ID, name)
  - All related layups
  - All related layers for each layup
- File format: `supplier_{id}.json`

Sample JSON structure:
```json
{
  "supplier": {
    "id": 1,
    "name": "Supplier Name",
    "created_at": "2026-02-19T08:00:00Z"
  },
  "layups": [
    {
      "id": 1,
      "name": "Layup Name",
      "layers": [
        {
          "id": 1,
          "layer_order": 1,
          "thickness": 10.5,
          "width": 100.0,
          "angle": 0
        }
      ]
    }
  ]
}
```

#### 3. Import Functionality

**Import by Supplier**
- Navigate to Import Supplier from Suppliers list
- Select target supplier
- Upload JSON, CSV, or Excel file
- Choose conflict resolution strategy:
  - **Skip Conflict**: Keep current data, ignore incoming changes
  - **Overwrite Existing**: Replace current data with imported data
  - **Duplicate Layup**: Create new layup with duplicate data
  - **Reject Entire Import**: Abort import if any conflicts found

#### 4. Conflict Resolution

**Conflict Detection**
- Layup-level conflicts: Same layup name under same supplier
- Layer-level conflicts: Same layer order with different thickness, width, or angle values

**Conflict Handling Strategies**
1. **Skip**: Keeps existing data, ignores incoming
2. **Overwrite**: Replaces existing with incoming data
3. **Duplicate**: For conflicts, duplicates the layup with imported data
4. **Reject**: Displays detailed conflict report and aborts import

**Conflict Review Interface**
- View all detected conflicts side-by-side
- Current data vs. Incoming data comparison
- Navigation through conflicts (1 of N)
- Detailed conflict information with field-level differences

### API Routes

```
Suppliers:
  GET    /suppliers                          (suppliers.index)
  POST   /suppliers                          (suppliers.store)
  GET    /suppliers/create                   (suppliers.create)
  GET    /suppliers/{supplier}               (suppliers.show)
  PUT    /suppliers/{supplier}               (suppliers.update)
  DELETE /suppliers/{supplier}               (suppliers.destroy)
  GET    /suppliers/{supplier}/edit          (suppliers.edit)

Layups:
  GET    /suppliers/{supplier}/layups                      (suppliers.layups.index)
  POST   /suppliers/{supplier}/layups                      (suppliers.layups.store)
  GET    /suppliers/{supplier}/layups/create               (suppliers.layups.create)
  GET    /suppliers/{supplier}/layups/{layup}             (suppliers.layups.show)
  PUT    /suppliers/{supplier}/layups/{layup}             (suppliers.layups.update)
  DELETE /suppliers/{supplier}/layups/{layup}             (suppliers.layups.destroy)
  GET    /suppliers/{supplier}/layups/{layup}/edit        (suppliers.layups.edit)

Layers:
  GET    /suppliers/{supplier}/layups/{layup}/layers                   (suppliers.layups.layers.index)
  POST   /suppliers/{supplier}/layups/{layup}/layers                   (suppliers.layups.layers.store)
  GET    /suppliers/{supplier}/layups/{layup}/layers/create            (suppliers.layups.layers.create)
  GET    /suppliers/{supplier}/layups/{layup}/layers/{layer}           (suppliers.layups.layers.show)
  PUT    /suppliers/{supplier}/layups/{layup}/layers/{layer}           (suppliers.layups.layers.update)
  DELETE /suppliers/{supplier}/layups/{layup}/layers/{layer}           (suppliers.layups.layers.destroy)
  GET    /suppliers/{supplier}/layups/{layup}/layers/{layer}/edit      (suppliers.layups.layers.edit)

Import/Export:
  GET    /import-export/export/{supplier}           (import-export.export-form)
  GET    /import-export/export-data/{supplier}      (import-export.export)
  GET    /import-export/import                      (import-export.import)
  POST   /import-export/import                      (import-export.import-action)
  GET    /import-export/conflict-review             (import-export.conflict-review)
  POST   /import-export/resolve-conflicts           (import-export.resolve-conflicts)
```

### Controllers

1. **SupplierController** (`app/Http/Controllers/SupplierController.php`)
   - Handles CRUD operations for suppliers
   - Methods: index, create, store, show, edit, update, destroy

2. **CltLayupController** (`app/Http/Controllers/CltLayupController.php`)
   - Handles CRUD operations for layups (nested under supplier)
   - Validates unique layup names per supplier
   - Methods: index, create, store, show, edit, update, destroy

3. **CltLayerController** (`app/Http/Controllers/CltLayerController.php`)
   - Handles CRUD operations for layers (nested under layup)
   - Validates layer properties and angles (0, 45, 90)
   - Methods: index, create, store, show, edit, update, destroy

4. **ImportExportController** (`app/Http/Controllers/ImportExportController.php`)
   - Handles export and import operations
   - Manages conflict resolution UI
   - Methods: exportForm, export, importForm, import, conflictReview, resolveConflicts

### Services

**ImportExportService** (`app/Services/ImportExportService.php`)
- `exportSupplier(Supplier $supplier)`: Exports supplier with all related data
- `importSupplier(Supplier $supplier, UploadedFile $file, string $strategy)`: Imports data with conflict detection
- `detectLayerConflict(CltLayer $existing, array $incoming)`: Detects field-level conflicts
- `parseFile(UploadedFile $file)`: Parses JSON/CSV files
- `parseCSV(UploadedFile $file)`: Converts CSV to array format
- `parseExcel(UploadedFile $file)`: Placeholder for Excel support

### Views

All views are located in `resources/views/`:

**Suppliers**
- `suppliers/index.blade.php` - List all suppliers
- `suppliers/create.blade.php` - Create form
- `suppliers/edit.blade.php` - Edit form
- `suppliers/show.blade.php` - Supplier details with layups

**Layups**
- `layups/index.blade.php` - List layups for supplier
- `layups/create.blade.php` - Create form
- `layups/edit.blade.php` - Edit form
- `layups/show.blade.php` - Layup details with layers

**Layers**
- `layers/index.blade.php` - List layers for layup
- `layers/create.blade.php` - Create form
- `layers/edit.blade.php` - Edit form
- `layers/show.blade.php` - Layer details

**Import/Export**
- `import-export/export.blade.php` - Export form
- `import-export/import.blade.php` - Import form with instructions
- `import-export/conflict-review.blade.php` - Conflict review interface

### Key Features

1. **Route Model Binding**: Uses implicit binding for easier URL parameter injection
2. **Nested Resources**: Proper URL structure for nested CRUD (supplier → layup → layer)
3. **Validation**: Server-side validation for all inputs with error messages
4. **Database Transactions**: Import operations use transactions to maintain data integrity
5. **Conflict Detection**: Automatic detection of:
   - Duplicate layup names within same supplier
   - Layer conflicts (same order with different properties)
6. **Flexible File Support**: JSON and CSV import formats supported
7. **Pagination**: List views paginated for better performance
8. **Dark Mode Support**: All views support dark mode with Tailwind CSS

## 4. Design Reference

A design reference is available in Figma:

[Figma Design File](https://www.figma.com/design/odWJ887r00aslmSFPIHMCx/SPEC-Toolbox---Feature-Test?node-id=11001-35&t=XUggOaUUi9p8jGFG-1)

> The design is for reference only. Exact visual matching is not required.

## 5. Evaluation Criteria

### Main Evaluation

- Correct implementation of the required features

### Bonus Evaluation

**Architecture & Design Patterns**

- Use Repository and/or Service pattern
- Bind interfaces via a Service Provider

**Laravel Best Practices**

- Form Request validation
- Policies or Gates for authorization
- Proper use of Route Model Binding
- Clean, maintainable code following Laravel conventions

**Automated Testing**

- Unit tests (validation, services, repositories)
- Feature tests (CRUD and import/export flows)

**Additional Improvements**

- Any meaningful enhancements will be considered positively

## 6. Submission

The deadline will be provided via email.  
Please ensure submission within the specified timeframe.


## 7. Demo

Include one of the following with your submission:

- A demo video (recommended), or
- A live project link

Ensure the demo clearly showcases:

- CRUD functionality
- Import / Export feature
- Conflict resolution behavior
