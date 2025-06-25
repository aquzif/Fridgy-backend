import {
    Button,
    Dialog,
    DialogActions,
    DialogContent,
    DialogTitle,
    FormControl,
    Grid,
    InputLabel,
    MenuItem,
    Select,
    TextField,
    Grow
} from "@mui/material";
import {forwardRef, useEffect, useRef} from "react";
import {useFormik} from "formik";
import {useSelector} from "react-redux";
import ShelfEntrySchema from "@/Schemas/ShelfEntrySchema";
import ShelfEntriesAPI from "@/API/ShelfEntriesAPI";
import {requestGlobalUnits} from "@/Store/Reducers/GlobalUnitReducer";
import store from "@/Store/store";
import toast from "react-hot-toast";

const Transition = forwardRef(function Transition(props, ref) {
    return <Grow ref={ref} {...props} />;
});

const ShelfEntryCEDialog = ({
    open = false,
    onClose = () => {},
    editMode = false,
    editEntry = null,
}) => {

    const {globalUnits} = useSelector(state => state.globalUnitReducer);
    const mainInput = useRef(null);

    const formik = useFormik({
        initialValues: {
            product_name: '',
            amount: 0,
            unit_id: '',
            bought_at: '',
            expires_at: '',
        },
        validationSchema: ShelfEntrySchema,
        onSubmit: async (values) => {
            try {
                if(editMode) {
                    await ShelfEntriesAPI.update(editEntry.id, values);
                } else {
                    await ShelfEntriesAPI.create(values);
                }
                onClose(true);
            } catch (e) {
                toast.error('Nie udało się zapisać danych');
            }
        }
    });

    useEffect(() => {
        if(open){
            store.dispatch(requestGlobalUnits());
            formik.resetForm();
            if(editMode && editEntry){
                formik.setValues({
                    product_name: editEntry.product_name,
                    amount: editEntry.amount,
                    unit_id: editEntry.unit_id || '',
                    bought_at: editEntry.bought_at ? editEntry.bought_at.substring(0,10) : '',
                    expires_at: editEntry.expires_at ? editEntry.expires_at.substring(0,10) : '',
                });
            } else {
                formik.setFieldValue('unit_id', globalUnits?.[0]?.id || '');
            }
            mainInput.current?.focus();
        }
    }, [open, editMode]);

    const handleClose = () => onClose(false);

    return (
        <Dialog open={open} TransitionComponent={Transition} fullWidth maxWidth={'sm'} onClose={handleClose}>
            <DialogTitle>{editMode ? 'Edytuj wpis' : 'Dodaj wpis'}</DialogTitle>
            <form onSubmit={formik.handleSubmit}>
                <DialogContent>
                    <Grid container spacing={2}>
                        <Grid item xs={12}>
                            <TextField
                                inputRef={mainInput}
                                variant={'standard'}
                                name={'product_name'}
                                label={'Nazwa'}
                                value={formik.values.product_name}
                                onChange={formik.handleChange}
                                fullWidth
                                error={formik.touched.product_name && Boolean(formik.errors.product_name)}
                                helperText={formik.touched.product_name && formik.errors.product_name}
                            />
                        </Grid>
                        <Grid item xs={12} md={6}>
                            <TextField
                                variant={'standard'}
                                name={'amount'}
                                type={'number'}
                                label={'Ilość'}
                                value={formik.values.amount}
                                onChange={formik.handleChange}
                                fullWidth
                                error={formik.touched.amount && Boolean(formik.errors.amount)}
                                helperText={formik.touched.amount && formik.errors.amount}
                            />
                        </Grid>
                        <Grid item xs={12} md={6}>
                            <FormControl fullWidth variant={'standard'}>
                                <InputLabel>Jednostka</InputLabel>
                                <Select
                                    value={formik.values.unit_id}
                                    name={'unit_id'}
                                    label={'Jednostka'}
                                    onChange={formik.handleChange}
                                >
                                    <MenuItem value=''><em>Brak</em></MenuItem>
                                    {globalUnits.map(unit => <MenuItem key={unit.id} value={unit.id}>{unit.name}</MenuItem>)}
                                </Select>
                            </FormControl>
                        </Grid>
                        <Grid item xs={12} md={6}>
                            <TextField
                                variant={'standard'}
                                name={'bought_at'}
                                type={'date'}
                                label={'Data zakupu'}
                                InputLabelProps={{shrink:true}}
                                value={formik.values.bought_at}
                                onChange={formik.handleChange}
                                fullWidth
                            />
                        </Grid>
                        <Grid item xs={12} md={6}>
                            <TextField
                                variant={'standard'}
                                name={'expires_at'}
                                type={'date'}
                                label={'Data ważności'}
                                InputLabelProps={{shrink:true}}
                                value={formik.values.expires_at}
                                onChange={formik.handleChange}
                                fullWidth
                            />
                        </Grid>
                    </Grid>
                </DialogContent>
                <DialogActions>
                    <Button color={'warning'} onClick={handleClose}>Anuluj</Button>
                    <Button type={'submit'}>{editMode ? 'Zapisz' : 'Dodaj'}</Button>
                </DialogActions>
            </form>
        </Dialog>
    );
};

export default ShelfEntryCEDialog;
