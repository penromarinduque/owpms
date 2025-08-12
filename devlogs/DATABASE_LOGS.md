## February 25, 2025
- Added specie_class_id to specie_families table and specie_type_id to specie_class table to make the implementation of specie filtering possible.


# March 13, 2025
- Added document column to permittees table.

# May 7, 2025
- Added legal_basis column to ltp_fees table

 # May 8, 2025
 - Added deleted_at column to ltp_fees table using Laravel's softDeletes()
 
# May 13, 2025
- Created new tables user_roles, roles, permissions and role_permissions
- restructure notifications table

# May 16, 2025
- Created new table generated_document_types with columns id, name, description
- Created new table signatory_roles with columns id, role, description
- Created new table signatories with column generated_document_type_id, user_id, order, signatory_role_id

# May 19, 2025 
- added document column do payment_orders table
- 