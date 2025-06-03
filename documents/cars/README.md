# Cars Module Documentation

## Overview
The Cars module provides functionality to manage the car inventory in the rental system. It includes features for adding, editing, viewing, and deleting cars, as well as managing their availability status.

## Features

### Layout Options
The cars listing page supports two different layout views:
- **Table Layout**: A traditional table view that shows detailed information in columns with sorting capabilities
- **Grid Layout**: A card-based grid view that emphasizes visual presentation with car images

Users can toggle between these layouts using the layout switch button in the top-right corner of the page. The layout preference is remembered across sessions.

### Car Management
- Add new cars with detailed information
- Edit existing car details
- View car information
- Delete cars from inventory
- Track car status (available, maintenance, reserved, rented)

### Filtering and Sorting
- Filter cars by:
  - Brand
  - Model
  - Year
  - Status
  - Price range
- Sort cars by:
  - Brand
  - Model
  - Year
  - Daily rate
  - Status

### Visual Features
- Car image display
- Status indicators with color coding
- Responsive design for all screen sizes
- Sketchy UI theme consistent with the application style

## Technical Details

### Layout Preference
The layout preference is managed through the `LayoutHelper` class and stored in the session:
```php
// Get current layout
$layout = \App\Helpers\LayoutHelper::getLayoutPreference('cars.index');

// Set layout preference
\App\Helpers\LayoutHelper::setLayoutPreference('cars.index', 'grid');
```

### Routes
- `GET /cars` - List all cars
- `GET /cars/create` - Show car creation form
- `POST /cars` - Store new car
- `GET /cars/{car}` - Show car details
- `GET /cars/{car}/edit` - Show car edit form
- `PUT/PATCH /cars/{car}` - Update car
- `DELETE /cars/{car}` - Delete car
- `POST /layout-preference` - Update layout preference

## Usage
1. Navigate to the Cars listing page
2. Use the layout toggle button to switch between table and grid views
3. Use filters and sorting options to find specific cars
4. Click on action buttons to view, edit, or delete cars
5. Add new cars using the "Add New Car" button 