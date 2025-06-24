import styled from "styled-components";
import {Checkbox, IconButton} from "@mui/material";
import {DeleteForever, Edit} from "@mui/icons-material";

const Container = styled.div`
  height: 160px;
  margin-bottom: 20px;
  border-radius: 20px;
  background-color: white;
  position: relative;
  &:hover {
    cursor: pointer;
  }
`;

const MealName = styled.p`
    padding: 2px 20px 2px 8px;
    background-color: white;
    display: block;
    width: fit-content;
    border-bottom-right-radius: 20px;
    color: gray;
`;

const RecipeName = styled.p`
    padding: 2px 10px 2px 8px;
    background-color: white;
    display: block;
    width: fit-content;
    border-top-right-radius: 20px;
    color: gray;
    max-width: 80%;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    position: absolute;
    bottom: 0;
`;

const RecipeEditButton = styled.div`
position: absolute;
  top: -6px;
  right: 24px;
  padding: 5px;
`;

const RecipeDeleteButton = styled.div`
  position: absolute;
  top: -6px;
  right: -6px;
  padding: 5px;
`;

const CalendarQuickEntry = ({meal, mealName, onClick, onEdit, selectMode, onCheck, onDelete}) => {
    const handleClick = (e) => {
        if(e.target.tagName !== 'BUTTON'
            && e.target.tagName !== 'svg'
            && e.target.tagName !== 'path'
            && e.target.tagName !== 'INPUT')
        {
            onClick();
        }
    }

    const item = meal.calendar_entry_quick_entries?.[0];

    return <Container onClick={handleClick}>
        <MealName>{mealName}</MealName>
        <RecipeName>{item?.name}</RecipeName>
        <RecipeEditButton>
            {!selectMode && <IconButton onClick={onEdit}><Edit sx={{color:'#FACC2C', backgroundColor:'white',borderRadius:'22px',padding:'2px'}} /></IconButton>}
        </RecipeEditButton>
        <RecipeDeleteButton>
            {selectMode ? <Checkbox sx={{color:'#FACC2C'}} checked={meal.selected} onChange={() => onCheck(meal.id)} /> : <IconButton onClick={() => onDelete(meal.id)}><DeleteForever sx={{color:'red', backgroundColor:'white',borderRadius:'22px',padding:'2px'}} /></IconButton>}
        </RecipeDeleteButton>
    </Container>
}

export default CalendarQuickEntry;
