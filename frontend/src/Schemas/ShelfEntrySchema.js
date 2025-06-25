import * as Yup from 'yup';

const ShelfEntrySchema = Yup.object().shape({
    product_name: Yup.string().required('Pole wymagane'),
    amount: Yup.number().min(0, 'Nieprawidłowa wartość'),
    unit_id: Yup.number().nullable(),
    bought_at: Yup.date().nullable(),
    expires_at: Yup.date().nullable(),
});

export default ShelfEntrySchema;
