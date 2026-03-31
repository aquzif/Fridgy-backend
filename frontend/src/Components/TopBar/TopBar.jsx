import styled from "styled-components";
import {Icon} from "@iconify/react/offline";
import {useMediaQuery} from "@/Hooks/useMediaQuery";
import menuIcon from '@iconify/icons-mdi/menu';
import barcodeScan from '@iconify/icons-mdi/barcode-scan';
import {useNavigate} from "react-router-dom";


const Container = styled.div`
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 64px;
    box-shadow: 0 8px 24px rgba(32, 48, 120, 0.08);
    border-bottom: 1px solid #e5eafb;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    box-sizing: border-box;
    padding: 0 18px;

    @media (max-width: 768px) {
        height: 70px;
        padding: 0 12px;
    }
`;

const OpenIcon = styled(Icon)`
    width: 28px;
    height: 28px;
    color: #4d5ab3;
    padding: 10px;
    border-radius: 12px;
    background: #eef2ff;
    border: 1px solid #d8e0ff;
`;

const Logo = styled.p`
    flex: 1;
    text-align: center;
    color: #1f295f;
    font-weight: bold;
    font-size: 24px;
    letter-spacing: 2px;
`;

const TopBar = ({
    onOpen
}) => {

    const isMobile = useMediaQuery('(max-width: 768px)');
    const navigate = useNavigate();



    return (
        <Container>
            {isMobile ? <>
                <OpenIcon icon={menuIcon} onClick={onOpen} />
                <Logo>Fridgy</Logo>
                <OpenIcon icon={barcodeScan} onClick={() => navigate('/skaner')} />
            </> : <>
                <Logo style={{ textAlign: 'left', fontSize: '22px' }}>Witaj w Fridgy 👋</Logo>
                <OpenIcon icon={barcodeScan} onClick={() => navigate('/skaner')} />
            </>}


        </Container>
    )

}

export default TopBar;
