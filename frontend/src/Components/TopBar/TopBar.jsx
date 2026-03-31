import styled from "styled-components";
import {Icon} from "@iconify/react/offline";
import {useMediaQuery} from "@/Hooks/useMediaQuery";
import menuIcon from '@iconify/icons-mdi/menu';
import barcodeScan from '@iconify/icons-mdi/barcode-scan';
import {useNavigate} from "react-router-dom";


const Container = styled.div`
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    height: 64px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    border-bottom: 1px solid #e2e8f0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    position: sticky;
    top: 0;
    z-index: 20;
    border-radius: 0 0 16px 16px;

    @media (max-width: 768px) {
        height: 70px;
    }
`;

const OpenIcon = styled(Icon)`
    margin: 10px 12px;
    width: 34px;
    height: 34px;
    color: #334155;
    padding: 6px;
    border-radius: 12px;

    &:hover {
        background: #e2e8f0;
        cursor: pointer;
    }
`;

const Logo = styled.p`
    width: calc(100vw - 140px);
    text-align: center;
    color: #0f172a;
    font-weight: 700;
    font-size: 28px;
    letter-spacing: 0.5px;
`;

const TopBar = ({
    onOpen
}) => {

    const isMobile = useMediaQuery('(max-width: 768px)');
    const navigate = useNavigate();



    return (
        <Container>
            {isMobile && <>
                <OpenIcon icon={menuIcon} onClick={onOpen} />
                <Logo>Fridgy</Logo>
                <OpenIcon icon={barcodeScan} onClick={() => navigate('/skaner')} />
            </>}


        </Container>
    )

}

export default TopBar;
