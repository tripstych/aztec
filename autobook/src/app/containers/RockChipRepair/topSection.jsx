import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import BgImg from "../../components/assets/images/bg_5.jpg";

const TopSectionContainer = styled.div`
  /* min-height: 400px; */
  margin-top: 2em;
  ${tw`
    w-full
    flex
    justify-between
  `}
`;

const HeaderSection = styled.div`
  ${tw`
    w-full
    h-[500px] 
    flex
    items-center
    justify-center
    text-white
  `}
  background: url(${BgImg}) center/cover no-repeat;
  background-attachment: fixed;
  position: relative;

  &::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.55); /* Adjust the opacity (0.5) as needed */
    z-index: 1;
  }

  > * {
    position: relative;
    z-index: 2;
  }
`;

const HeaderText = styled.h1`
  ${tw`
    text-3xl 
    md:text-6xl 
    tracking-wide 
    font-extrabold 
    text-white
  `}
`;

const TopSection = () => {
  return (
    <TopSectionContainer>
      <HeaderSection>
        <HeaderText>
          Rock Chip <span className="text-cBlue">Repair</span>
        </HeaderText>
      </HeaderSection>
    </TopSectionContainer>
  );
};

export default TopSection;
