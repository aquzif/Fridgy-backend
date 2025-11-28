import {useParams} from "react-router-dom";
import styled from "styled-components";
import {useEffect, useRef, useState} from "react";
import ProductsAPI from "@/API/ProductsAPI";
import toast from "react-hot-toast";
import ProductAPI from "@/API/RecipesAPI";
import {Box, Button, Chip, FormControl, Grid, InputLabel, MenuItem, Select, Tab, Tabs, TextField} from "@mui/material";
import placeholderImage from "@/Assets/placeholder.png";
import RecipesAPI from "@/API/RecipesAPI";
import NetworkUtils from "@/Utils/NetworkUtils";
import FAB from "@/Components/FAB/FAB";
import {Add, Delete, Edit, Save} from "@mui/icons-material";
import {useFormik} from "formik";
import RecipeSchema from "@/Schemas/RecipeSchema";
import DataTable from "@/Components/DataTable/DataTable";
import LongTextEditDialog from "@/Dialogs/LongTextEditDialog";
import IngredientCEDialog from "@/Dialogs/IngredientCEDialog";
import CustomTabPanel from "@/Components/CustomTabPanel/CustomTabPanel";
import Multiselector from "@/Components/Multiselector/Multiselector";
import {useSelector} from "react-redux";
import recipeTagsReducer, {requestRecipeTags} from "@/Store/Reducers/RecipeTagsReducer";
import store from "@/Store/store";
import RecipeIngredientsAPI from "@/API/RecipeIngredientsAPI";
import RecipeVariantsAPI from "@/API/RecipeVariantsAPI";

const Container = styled.div`
  width: calc(100% - 100px);
  background-color: white;
  min-height: calc(100% - 100px);
  margin: 20px auto;
  padding: 30px;
  border-radius: 10px;
`;

const Image = styled.img`
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e0e0e0;
  
  
    &:hover{
      border: 2px solid #FACC2C;
      cursor: pointer;
    }
`;

const columns = [
    {
        'name': 'id',
        'label': 'ID',
    },{
        'name': 'step',
        'label': 'Krok',
    }
    ];

const ingredientsColumns = [
    {
        'name': 'id',
        'label': 'ID',
    },{
        'name': 'name',
        'label': 'Nazwa',
    },{
        'name': 'kcal',
        'label': 'Kalorie',
    },{
        'name': 'amount',
        'label': 'Ilość',
    }
];




const RecipeEditView = () => {

    const inputFileRef = useRef(null);

    const {recipeTags} = useSelector(state => state.recipeTagsReducer);

    const { id } = useParams();
    const [product,setProduct] = useState(null);
    const [isLoading,setIsLoading] = useState(true);
    const [tab,setTab] = useState(0);
    const [oldImagePath, setOldImagePath] = useState(placeholderImage);
    const [stepDialogOpen, setStepDialogOpen] = useState(false);
    const [stepDialogEdit, setStepDialogEdit] = useState(0);
    const [stepDialogStartText, setStepDialogStartText] = useState('');
    const [ingredientDialogOpen, setIngredientDialogOpen] = useState(false);
    const [ingredientDialogEdit, setIngredientDialogEdit] = useState(0);
    const [variants,setVariants] = useState([]);
    const [selectedVariantId,setSelectedVariantId] = useState(null);
    const [variantName,setVariantName] = useState('');
    const [newVariantName,setNewVariantName] = useState('');
    const [newImage, setNewImage] = useState({
        selected: false,
        url: '',
        file: null
    });

    const syncVariants = (recipeData) => {
        const recipeVariants = recipeData?.variants || [];
        setVariants(recipeVariants);

        const defaultVariant = recipeVariants.find((variant) => variant.is_default) || recipeVariants[0];
        setSelectedVariantId(defaultVariant?.id || null);
        setVariantName(defaultVariant?.name || '');
    }

    const load = async () => {
        store.dispatch(requestRecipeTags());
        setIsLoading(true);

        const response = await ProductAPI.get(id);

        if(response.code >= 400){
            toast.error('Nie udało się pobrać produktu');
            return;
        }

        let {data} = response.data;

        setProduct(data);
        syncVariants(data);

        console.log('LOAD');
        setOldImagePath(NetworkUtils.fixBackendUrl(data?.image) || placeholderImage);
        setIsLoading(false);
    }

    useEffect(() => {
        load();
    }, []);

    const updateImage = async () => {
        console.log(newImage.file);
        if(newImage?.file)
            await toast.promise(RecipesAPI.update(id,{
                image: newImage.file
            }),{
                loading: 'Zapisywanie zdjęcia...',
                success: 'Zdjęcie zostało zapisane',
                error: 'Nie udało się zapisać zdjęcia'
            })

    }

    useEffect(() => {
        updateImage();
    }, [newImage]);

    const selectedVariant = variants.find((variant) => variant.id === selectedVariantId) || null;

    const handleVariantChange = (value) => {
        setSelectedVariantId(value);
        const variant = variants.find((variant) => variant.id === value);
        setVariantName(variant?.name || '');
    }

    const createVariant = async () => {
        if(!newVariantName.trim()){
            toast.error('Podaj nazwę wariantu');
            return;
        }

        await toast.promise(RecipeVariantsAPI.create(id,{
            name: newVariantName
        }),{
            loading: 'Dodawanie wariantu...',
            success: 'Dodano wariant',
            error: 'Nie udało się dodać wariantu'
        });

        setNewVariantName('');
        await load();
    }

    const saveVariantName = async () => {
        if(!selectedVariantId){
            toast.error('Wybierz wariant');
            return;
        }

        await toast.promise(RecipeVariantsAPI.update(id, selectedVariantId,{
            name: variantName
        }),{
            loading: 'Aktualizowanie wariantu...',
            success: 'Wariant został zaktualizowany',
            error: 'Nie udało się zaktualizować wariantu'
        });

        await load();
    }

    const setDefaultVariant = async () => {
        if(!selectedVariantId){
            toast.error('Wybierz wariant');
            return;
        }

        await toast.promise(RecipeVariantsAPI.update(id, selectedVariantId,{
            is_default: true
        }),{
            loading: 'Ustawianie wariantu domyślnego...',
            success: 'Ustawiono wariant domyślny',
            error: 'Nie udało się ustawić wariantu domyślnego'
        });

        await load();
    }

    const deleteVariant = async () => {
        if(!selectedVariantId){
            toast.error('Wybierz wariant');
            return;
        }

        await toast.promise(RecipeVariantsAPI.delete(id, selectedVariantId),{
            loading: 'Usuwanie wariantu...',
            success: 'Usunięto wariant',
            error: 'Nie udało się usunąć wariantu'
        });

        setSelectedVariantId(null);
        await load();
    }

    const formik = useFormik({
        initialValues: {
            'name': '',
            'prepare_time': 0,
            'serving_amount': 0,
            'steps': [],
            'video_url': '',
            'tags': []
        },
        validationSchema: RecipeSchema,
        onSubmit: async (values) => {

            await toast.promise(RecipesAPI.update(id,{
                ...values,
                steps: JSON.stringify(values.steps),
                tags: JSON.stringify(values.tags)
            }),{
                loading: 'Zapisywanie przepisu...',
                success: 'Przepis został zapisany',
                error: 'Nie udało się zapisać przepisu'
            })
            load();
        }
    });

    const onTagSelect = (val) => {
        formik.setValues({
            ...formik.values,
            tags: val.map((tag) => tag.id),
        });
    }

    useEffect(() => {
        if(product){
            console.log('PRODUCT',product);
            formik.setValues({
                'name': product.name,
                'prepare_time': product.prepare_time,
                'serving_amount': product.serving_amount,
                'steps': JSON.parse(product.steps) || [],
                'video_url': product.video_url || '',
                'tags': JSON.parse(product?.tags || '[]')
            })
        }
    }, [product]);

    const onFileChangeCapture = (e) => {

        if(e.target.files.length === 0){
            setNewImage({
                selected: false,
                url: '',
                file: null
            })
            //updateImage();
            //onChange(e);
        }else{

            let types = [
                'image/png',
                'image/jpg',
                'image/jpeg'
            ]

            if(types.includes(e.target.files[0].type)) {
                setNewImage({
                    selected: true,
                    url: URL.createObjectURL(e.target.files[0]),
                    file: e.target.files[0]
                });
                //updateImage();
            }
        }


    }

    const tools = [
        {
            'name': 'add',
            'label': 'Dodaj krok',
            'icon': <Add />,
            'onClick': async () => {
                setStepDialogOpen(true);
                setStepDialogEdit(false);
                setStepDialogStartText('');
            }
        }
    ];

    const ingredientsTools = [
        {
            'name': 'add',
            'label': 'Dodaj składnik',
            'icon': <Add />,
            'onClick': async () => {
                if(!selectedVariantId){
                    toast.error('Wybierz wariant, aby dodać składniki');
                    return;
                }
                setIngredientDialogOpen(true);
                setIngredientDialogEdit(0);
            }
        }
    ]

    const ingredientsInlineTools = [
        {
            'name': 'edit',
            'label': 'Edytuj',
            'icon': <Edit />,
            'onClick': async ({id}) =>{
                if(!selectedVariantId){
                    toast.error('Wybierz wariant');
                    return;
                }
                setIngredientDialogOpen(true);
                setIngredientDialogEdit(id);
            }
        },{
            'name': 'delete',
            'label': 'Usuń',
            'icon': <Delete color={'error'} />,
            'onClick': async ({id: rowId}) => {
                toast.promise(RecipeIngredientsAPI.delete(id, selectedVariantId, rowId),{
                    loading: 'usuwania składnika...',
                    success: 'Składnik został usunięty',
                    error: 'Nie udało się usunąć składnika'
                }).then(() => {
                    load();
                });
            }
        }
    ];

    const handleTabChange = (event, newValue) => {
        setTab(newValue);
    };

    const handleImageClick = () => {
        inputFileRef.current.click();
    }

    const longTextDialogSubmit = (text) => {
        if(stepDialogEdit){
            formik.values.steps[stepDialogEdit-1] = text;
        }else{
            formik.values.steps.push(text);
        }
        setStepDialogOpen(false);
        setStepDialogStartText('');
        setStepDialogEdit(0);
    }

    const longTextDialogCancel = () => {
        setStepDialogOpen(false);
        setStepDialogStartText('');
        setStepDialogEdit(0);
    }

    console.log('TAGS',formik.values.tags);

    return <Container>

        <LongTextEditDialog
            open={stepDialogOpen}
            onReject={longTextDialogCancel}
            onSave={longTextDialogSubmit}
            startText={stepDialogStartText}
            title={stepDialogEdit ? 'Edytuj krok' : 'Dodaj krok'}
        />
        <IngredientCEDialog
            editId={ingredientDialogEdit}
            editMode={ingredientDialogEdit > 0}
            editRecipeId={id}
            editVariantId={selectedVariantId}
            open={ingredientDialogOpen}
            onClose={(success) => {
                setIngredientDialogOpen(false);
                if(success){
                    load();
                }
            }}
        />
        <h2>{
            isLoading ? 'Ładowanie...' : product.name + ' ('+Math.round(selectedVariant?.calories_per_serving || product.calories_per_serving)+' kcal)'
        }</h2>
        <FAB
            icon={<Save/>}
            onClick={formik.handleSubmit}
        />
        <Box sx={{ width: '100%' }}>
            <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                <Tabs value={tab} onChange={handleTabChange} aria-label="basic tabs example" centered={true} >
                    <Tab label="Podstawowe"  />
                    <Tab label="Przepis"  />
                    <Tab label="Składniki"  />
                </Tabs>
            </Box>
            <CustomTabPanel value={tab} index={0}>
                <input
                    type="file"
                    ref={inputFileRef}
                    onChangeCapture={onFileChangeCapture}
                    name={'image'}
                    hidden={true}
                />
                <Grid container spacing={8}>
                    <Grid item xs={12} md={6}>
                        <Image src={newImage.selected && newImage.url || oldImagePath}
                            onClick={handleImageClick}
                        />
                    </Grid>
                    <Grid item xs={12} md={6}>
                        <Grid container spacing={2}>
                            <Grid item xs={12}>
                                <TextField
                                    variant={'standard'}
                                    name={'name'}
                                    label={'Nazwa'}
                                    value={formik.values.name}
                                    onChange={formik.handleChange}
                                    fullWidth
                                    error={formik.touched.name && Boolean(formik.errors.name)}
                                    helperText={formik.touched.name && formik.errors.name}
                                />
                            </Grid>
                            <Grid item xs={12} lg={6}>
                                <TextField
                                    variant={'standard'}
                                    name={'prepare_time'}
                                    label={'Czas przygotowania (w minutach)'}
                                    value={formik.values.prepare_time}
                                    onChange={formik.handleChange}
                                    fullWidth
                                    error={formik.touched.prepare_time && Boolean(formik.errors.prepare_time)}
                                    helperText={formik.touched.prepare_time && formik.errors.prepare_time}
                                />
                            </Grid>
                            <Grid item xs={12} lg={6} >
                                <TextField
                                    variant={'standard'}
                                    name={'serving_amount'}
                                    label={'Przewidziana ilość porcji'}
                                    value={formik.values.serving_amount}
                                    onChange={formik.handleChange}
                                    fullWidth
                                    error={formik.touched.serving_amount && Boolean(formik.errors.serving_amount)}
                                    helperText={formik.touched.serving_amount && formik.errors.serving_amount}
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    variant={'standard'}
                                    name={'video_url'}
                                    label={'Link do filmu na YouTube'}
                                    value={formik.values.video_url}
                                    onChange={formik.handleChange}
                                    fullWidth
                                    error={formik.touched.video_url && Boolean(formik.errors.video_url)}
                                    helperText={formik.touched.video_url && formik.errors.video_url}
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <FormControl fullWidth variant={'standard'}>
                                    <InputLabel>Wariant</InputLabel>
                                    <Select
                                        value={selectedVariantId || ''}
                                        onChange={(val) => handleVariantChange(val.target.value)}
                                    >
                                        {variants.map((variant) => (
                                            <MenuItem key={variant.id} value={variant.id}>
                                                {variant.name} {variant.is_default ? '(domyślny)' : ''}
                                            </MenuItem>
                                        ))}
                                    </Select>
                                </FormControl>
                            </Grid>
                            <Grid item xs={12} lg={6}>
                                <TextField
                                    variant={'standard'}
                                    name={'variant_name'}
                                    label={'Nazwa wybranego wariantu'}
                                    value={variantName}
                                    onChange={(e) => setVariantName(e.target.value)}
                                    fullWidth
                                />
                            </Grid>
                            <Grid item xs={12} lg={6}>
                                <div style={{display: 'flex', flexDirection: 'row', gap: '10px', flexWrap: 'wrap'}}>
                                    <Button variant={'contained'} onClick={saveVariantName}>Zapisz nazwę</Button>
                                    <Button variant={'outlined'} onClick={setDefaultVariant}>Ustaw domyślny</Button>
                                    <Button variant={'outlined'} color={'error'} onClick={deleteVariant}>Usuń wariant</Button>
                                </div>
                            </Grid>
                            <Grid item xs={12} lg={6}>
                                <TextField
                                    variant={'standard'}
                                    name={'new_variant_name'}
                                    label={'Nazwa nowego wariantu'}
                                    value={newVariantName}
                                    onChange={(e) => setNewVariantName(e.target.value)}
                                    fullWidth
                                />
                            </Grid>
                            <Grid item xs={12} lg={6}>
                                <Button variant={'contained'} onClick={createVariant}>Dodaj wariant</Button>
                            </Grid>
                            <Grid item xs={12}>
                                <Multiselector
                                    title={'Tagi'}
                                    options={recipeTags}
                                    renderTags={(value, getTagProps) =>
                                        value.map((option, index) => (
                                            <Chip variant="outlined" label={option.name} {...getTagProps({ index })}
                                                sx={{
                                                    backgroundColor: option.color
                                                }}
                                            />
                                        ))
                                    }
                                    onChange={onTagSelect}
                                    selected={recipeTags.filter((tag) => {
                                        return formik.values.tags.includes(tag.id);
                                    })}
                                />

                            </Grid>
                        </Grid>
                    </Grid>
                </Grid>
            </CustomTabPanel>
            <CustomTabPanel value={tab} index={1}>
                <DataTable
                    columns={columns}
                    title={'Kroki'}
                    tools={tools}
                    inlineTools={[
                        {
                            'name': 'delete',
                            'label': 'Usuń',
                            'icon': <Delete />,
                            'onClick': async (row) => {
                                formik.values.steps.splice(row.id-1,1);
                                formik.setValues({
                                    ...formik.values,
                                    steps: formik.values.steps
                                })
                            }
                        }
                    ]}
                    onRowClick={(rowID) => {
                        setStepDialogOpen(true);
                        console.log(rowID);
                        setStepDialogEdit(rowID);
                        setStepDialogStartText(formik.values.steps[rowID-1]);
                    }}
                    searchBar={true}
                    data={
                        formik.values.steps.map((step,index) => {
                            return {
                                id: index+1,
                                step: step
                            }
                        })
                    }
                />
            </CustomTabPanel>
            <CustomTabPanel value={tab} index={2}>
                <DataTable
                    columns={ingredientsColumns}
                    tools={ingredientsTools}
                    inlineTools={ingredientsInlineTools}
                    searchBar={true}
                    data={selectedVariant?.ingredients?.map((ingredient) =>{
                        return {
                            id: ingredient.id,
                            name: ingredient.product.name,
                            kcal: Math.round(ingredient.calories)+ ' kcal',
                            amount: Math.fround(ingredient.amount_in_unit) +' x '+ ingredient.unit.name
                                + ' ('+Math.fround(ingredient.amount_in_grams)+'g)'
                        }
                    }) || []}
                    title={'Składniki'}

                />
            </CustomTabPanel>
        </Box>
    </Container>

}

export default RecipeEditView;