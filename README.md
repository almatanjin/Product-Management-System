# Product Management System 
 
A web application built with Laravel and Vue.js for Product Manage.
 
This project is dockerized for easy setup and deployment and are designed to be ensuring compatibility.
 
## Setup and Installation
 
### Prerequisites
Docker and Docker Compose installed on your machine.
 
### Steps
1. **Clone the repository**:
   ```bash
   git clone https://github.com/almatanjin/product-management-system.git
   cd product-management-system
   ```
 
2. **Setting up the Product Management System for backend**:
   - Navigate to the Laravel project directory:
     ```bash
     cd product-management-system
     ```
   - Build and start the Docker containers:
     ```bash
     ./vendor/bin/sail up
     ./vendor/bin/sail artisan migrate
     ```
   - Access the application at `http://localhost`
 
## Usage
 
- **Managing Products and Categories**:
# Product and Feature Management

## 1. Relationships

- **Products and Features**:
  - A product can have multiple features.
  - Features are associated with a specific product.

## 2. Feature Addition

- **Add Features**:
  - There will be an option to add multiple features while creating a product.

## 3. Product Deletion

- **Cascade Deletion**:
  - If a product is deleted, all features related to that product will be automatically deleted.

# Product Upload Form Design

## 1. Form Creation

- **Product Upload Form**:
  - Design the product upload form using **Tailwind CSS**.
  - The form should allow the user to:
    - Input the product title.
    - Upload a product image.
    - Select multiple categories.
    - Add multiple features.


# Product and Feature Management

## 1. Relationships

- **Products and Features**:
  - A product can have multiple features.
  - Features are associated with a specific product.

## 2. Feature Addition

- **Add Features**:
  - There will be an option to add multiple features while creating a product.

## 3. Product Deletion

- **Cascade Deletion**:
  - If a product is deleted, all features related to that product will be automatically deleted.

# Product Upload Form Design

## 1. Form Creation

- **Product Upload Form**:
  - Design the product upload form using **Tailwind CSS**.
  - The form should allow the user to:
    - Input the product title.
    - Upload a product image.
    - Select multiple categories.
    - Add multiple features.

## Contributing
 
Contributions are welcome! Please submit a pull request or open an issue for any suggestions or bug reports.
 
## License
 
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
 
---
