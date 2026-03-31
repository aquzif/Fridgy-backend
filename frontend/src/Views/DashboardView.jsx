import {Outlet, useLocation} from "react-router-dom";
import Navigation from "@/Components/Navigation/Navigation";
import {useEffect, useState} from "react";
import styled from "styled-components";
import TopBar from "@/Components/TopBar/TopBar";
import {useMediaQuery} from "@/Hooks/useMediaQuery";


const Container = styled.div`
    width: calc(100vw - 280px);
    height: 100vh;
    background: radial-gradient(circle at top right, #eef2ff 0%, #f8faff 45%, #f4f7ff 100%);
    transition: width 0.2s ease-in;

    @media (max-width: 768px) {
        width: 100vw;
    }

`;

const Flex = styled.div`
    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-direction: row;
    background-color: #f4f7ff;
`;

const OutletContainer = styled.div`
  width: 100%;
  overflow: auto;
  padding: 20px;
  box-sizing: border-box;

  @media (max-width: 768px) {
      padding: 14px;
  }
`;

const DashboardView = () => {

    const [navOpen, setNavOpen] = useState(true);

    const mobile = useMediaQuery('(max-width: 768px)');
    const loc = useLocation();

    useEffect(() => {
        setNavOpen(false);
    }, [ loc ]);

    const onNavClick = () => setNavOpen(!navOpen);




    return (
        <Flex>
            <Navigation open={navOpen} onClose={onNavClick} />
            <Container>
                <TopBar onOpen={onNavClick} />
                <OutletContainer
                    style={{
                        height: mobile ? 'calc(100% - 71px)' : 'calc(100% - 51px)'
                    }}
                >
                    <Outlet />
                </OutletContainer>
            </Container>
        </Flex>
    )


}

export default DashboardView;
