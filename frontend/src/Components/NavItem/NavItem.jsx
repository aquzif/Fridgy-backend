import styled from "styled-components";
import {useMatch, useNavigate} from "react-router-dom";
import {Icon} from "@iconify/react/offline";


const Container = styled.div`
    border-radius: 12px;
    border: 1px solid transparent;
    max-width: 222px;
    margin: 8px auto;
    display: flex;
    flex-direction: row;
    align-items: center;
    transition: all 0.15s ease-in-out;

    &:hover {
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: rgba(148, 163, 184, 0.12);
        cursor: pointer;
        transform: translateX(2px);
    }

    @media (max-width: 768px) {
        max-width: 340px;
        margin: 12px auto;
    }
`;

const Title = styled.p`
    color: #94a3b8;
    font-size: 15px;
    padding: 12px 0;
    font-weight: 600;

    @media (max-width: 768px) {
        font-size: 19px;
        padding: 14px 0;
    }
`;

const StyledIcon = styled(Icon)`
    width: 24px;
    height: 24px;
    padding: 8px 16px;
    color: #64748b;

    @media (max-width: 768px) {
        width: 32px;
        height: 32px;
        padding: 10px 16px;
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

     const color = match ? '#e2e8f0' : '#64748b';
     const containerStyle = match ? {
         background: 'linear-gradient(90deg, rgba(14, 165, 233, 0.35) 0%, rgba(59, 130, 246, 0.22) 100%)',
         border: '1px solid rgba(56, 189, 248, 0.38)',
     } : {};

    return (
        <Container onClick={handleClick} style={containerStyle} >
            <StyledIcon style={{color}} icon={icon} />
            <Title style={{color}} >{name}</Title>
        </Container>
    )

}

export default NavItem;
