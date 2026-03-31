import styled from "styled-components";
import {Icon} from "@iconify/react/offline";


const Item = styled(Icon)`
    width: 22px;
    height: 22px;
    padding: 10px;
    color: #dce2ff;
    border-radius: 10px;
    margin: 0;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(221, 229, 255, 0.2);
    transition: all 0.2s ease-in-out;

    &:hover {
        cursor: pointer;
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.16);
    }

    @media (max-width: 768px) {
        width: 26px;
        height: 26px;
    }

`;

const MiniNavItem = ({
    icon,
    onClick
}) => {

    return <Item icon={icon} onClick={onClick} />

}

export default MiniNavItem;
