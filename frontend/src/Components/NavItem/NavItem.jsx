import styled from "styled-components";
import {useMatch, useNavigate} from "react-router-dom";
import {Icon} from "@iconify/react/offline";


const Container = styled.div`
    background-color: transparent;
    border-radius: 12px;
    border: 1px solid transparent;
    max-width: 100%;
    margin: 4px 8px;
    display: flex;
    flex-direction: row;
    align-items: center;
    transition: all 0.2s ease-in;

    &:hover {
        border: 1px solid rgba(176, 190, 255, 0.45);
        background: rgba(255, 255, 255, 0.08);
        cursor: pointer;
        transform: translateX(2px);
    }

    @media (max-width: 768px) {
        margin: 8px auto;
        max-width: 420px;
    }
`;

const Title = styled.p`
    color: #a8b3ed;
    font-size: 15px;
    padding: 10px 0;
    font-weight: 600;

    @media (max-width: 768px) {
        font-size: 18px;
        padding: 14px 0;
    }
`;

const StyledIcon = styled(Icon)`
    width: 22px;
    height: 22px;
    padding: 10px 14px;
    color: #8d9af0;

    @media (max-width: 768px) {
        width: 24px;
        height: 24px;
        padding: 14px 16px;
    }

`;

const NavItem = (
    {
        name,
        icon,
        url
    }
) => {

     const match = useMatch(url);
     const navigate = useNavigate();

     const handleClick = () => {
         navigate(url);
     }

    const color = match ? '#f2f4ff' : '#8d9af0';

    return (
        <Container onClick={handleClick} style={match ? { background: 'linear-gradient(90deg, rgba(91,108,255,0.35), rgba(59,198,173,0.18))', borderColor: 'rgba(170, 184, 255, 0.55)' } : {}}>
            <StyledIcon style={{color}} icon={icon} />
            <Title style={{color}}>{name}</Title>
        </Container>
    )

}

export default NavItem;
