import styled from "styled-components";
import {useEffect, useState} from "react";
import ShelfEntriesAPI from "@/API/ShelfEntriesAPI";
import DataTable from "@/Components/DataTable/DataTable";
import {Add, Delete, Edit} from "@mui/icons-material";
import toast from "react-hot-toast";
import ShelfEntryCEDialog from "@/Dialogs/ShelfEntryCEDialog";

const Container = styled.div`
  min-height: calc(100% - 100px);
  margin: 20px auto;
  padding: 30px;
  border-radius: 10px;
  width: calc(100% - 100px);
`;

const columns = [
    {name:'id', label:'ID'},
    {name:'product_name', label:'Nazwa'},
    {name:'amount', label:'Ilość'},
    {name:'unit_name', label:'Jednostka'},
    {name:'bought_at', label:'Data zakupu'},
    {name:'expires_at', label:'Data ważności'},
];

const ShelfView = () => {
    const [entries,setEntries] = useState([]);
    const [isLoading,setIsLoading] = useState(false);
    const [selectedIds,setSelectedIds] = useState([]);
    const [dialogOpen,setDialogOpen] = useState(false);
    const [editMode,setEditMode] = useState(false);
    const [editEntry,setEditEntry] = useState(null);

    const load = async () => {
        setIsLoading(true);
        const res = await ShelfEntriesAPI.getAll();
        if(res.status === 200) setEntries(res.data.data);
        setIsLoading(false);
    };

    useEffect(() => { load(); }, []);

    const handleDialogClose = (success) => {
        setDialogOpen(false);
        setEditMode(false);
        setEditEntry(null);
        if(success) load();
    };

    const deleteSelected = async () => {
        for(const id of selectedIds){
            await ShelfEntriesAPI.delete(id);
        }
        load();
    };

    const tools = [
        {name:'add', label:'Dodaj', icon:<Add />, onClick:() => {setEditMode(false); setDialogOpen(true);}},
        {name:'delete', label:'Usuń', icon:<Delete />, forSelect:true, onClick:() => toast.promise(deleteSelected(),{loading:'Usuwanie...',success:'Usunięto',error:'Błąd'})}
    ];

    const inlineTools = [
        {name:'edit', label:'Edytuj', icon:<Edit />, onClick:(row) => {setEditEntry(row); setEditMode(true); setDialogOpen(true);}}
    ];

    return <Container>
        <ShelfEntryCEDialog open={dialogOpen} onClose={handleDialogClose} editMode={editMode} editEntry={editEntry} />
        <DataTable
            title={'Szafka'}
            isLoading={isLoading}
            searchBar={true}
            data={entries}
            columns={columns}
            tools={tools}
            inlineTools={inlineTools}
            onSelect={setSelectedIds}
        />
    </Container>;
};

export default ShelfView;
