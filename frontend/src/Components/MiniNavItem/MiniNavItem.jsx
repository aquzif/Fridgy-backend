import styled from "styled-components";
import {Icon} from "@iconify/react/offline";


const Item = styled(Icon)`
    width: 22px;
    height: 22px;
    padding: 6px;
    color: #cbd5e1;
    margin: 0px 8px;
    border-radius: 10px;
    transition: all 0.15s ease-in-out;

    &:hover {
        color: #f8fafc;
        background: rgba(148, 163, 184, 0.22);
        cursor: pointer;
    }

    @media (max-width: 768px) {
        width: 40px;
        height: 40px;
        padding: 5px;
    }

`;

const MiniNavItem = ({
    icon,
    onClick
}) => {

    return <Item icon={icon} onClick={onClick} />

}

export default MiniNavItem;
