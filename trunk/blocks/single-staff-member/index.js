/**
 * Block dependencies
 */
import './style.scss';
import './editor.scss';

/**
 * Custom components
 */
import PostSelector from '../components/PostSelector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { decodeEntities } = wp.htmlEntities;
const { apiFetch } = wp;
const {
    ServerSideRender,
    PanelBody,
    PanelRow,
    SelectControl,
    ToggleControl
} = wp.components;

const renderAdminStaffMember = props => {
    const { id, layout, content, className } = props.attributes;

    if ( ! id )
        return <p>Please select a Staff Member.</p>;

    return (
        <ServerSideRender
            block="simple-staff-list/single-staff-member"
            attributes={{
                id: id,
                layout: layout,
                content: content,
            }}
        />
    );
}

const maybeShowContentOptions = ( layout, content, setAttributes ) => {

    if ( 'staff-loop-template' !== layout ) {
        return(
            <PanelBody
                title={__("Content Options", "simple-staff-list")}
                initialOpen={false}
            >
                <p>Turn specific fields on or off.</p>
                {content.map( (option, i) => {
                    return(
                        <ToggleControl
                            key={i}
                            label={__(option.label, "simple-staff-list")}
                            checked={content[i].value}
                            onChange={newValue => {
                                const newContent = content.map(a => ({...a}));
                                newContent[i].value = newValue;
                                setAttributes({ 
                                    content: newContent
                                })
                            }}
                        />
                    )
                })}
            </PanelBody>
        )
    }

    return ''
}

/**
 * Register block
 */
export default registerBlockType(
    'simple-staff-list/single-staff-member',
    {
        title: __( 'Single Staff Member', 'simple-staff-list' ),
        description: __( 'Show a single staff member block.', 'simple-staff-list' ),
        category: 'common',
        icon: 'groups',
        keywords: [
            __( 'simple staff member', 'simple-staff-list' ),
            __( 'simple single', 'simple-staff-list' ),
            __( 'staff list', 'simple-staff-list' ),
        ],
        edit: props => {
          const { attributes: { id, layout, content, staffData, availableLayouts },
          className, setAttributes } = props;

          // Fetch the staff data if we have a staff ID but no staffData. This covers the scenario of opening a post with an existing Single Staff Member block.
          if ( id && ! staffData ) {
            apiFetch({
              path: `/wp/v2/staff-member/${id}?_embed`
            }).then(response => {
                const fullpost = {
                    title: decodeEntities(response.title.rendered),
                    id: response.id,
                    url: response.link,
                    media: response._embedded && response._embedded['wp:featuredmedia'] ? response._embedded['wp:featuredmedia'][0] : null,
                    position: response.staffData.title,
                    bio: response.staffData.bio,
                    phone: response.staffData.phone,
                    email: response.staffData.email,
                    fb: response.staffData.fb,
                    tw: response.staffData.tw,
                };
                // send data to the block;
                setAttributes({
                    staffData: fullpost
                });
            });
          }

          // Fetch the available layouts if we don't have them yet.
          if ( ! availableLayouts ) {
              apiFetch({
                  path: `/sslp/v1/block-layouts/?context=single`
              }).then(response => {
                  setAttributes({
                      availableLayouts: response
                  })
              })
          }

          return (
            <div className={ className }>
              <InspectorControls>
                <PanelBody>
                    <PanelRow>
                        <PostSelector
                        onPostSelect={post => {
                            setAttributes({
                            staffData: post,
                            id: post.id,
                            });
                        }}
                        inputPlaceholder='Type to search Staff Members'
                        hideInputOnLimit={true}
                        posts={ staffData ? [staffData] : [] }
                        onChange={newValue => {
                            const newStaffData = newValue.length === 0 ? undefined : newValue;
                            setAttributes({
                            staffData: newStaffData,
                            id: newValue.id,
                            });
                        }}
                        postType={'staff-member'}
                        limit="1"
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label={__("Choose Layout", "simple-staff-list")}
                            value={layout}
                            options={ availableLayouts }
                            onChange={layout => setAttributes({ layout })}
                        />
                    </PanelRow>
                </PanelBody>
                {
                    maybeShowContentOptions( layout, content, setAttributes )
                }
              </InspectorControls>
              <div className={`sslp-layout_${layout}`}>
                {
                    ! staffData ? <p>Select a Staff Member</p> : renderAdminStaffMember(props)
                }
              </div>
            </div>
          );
        },
        save: props => {
            return null;
        },
    },
);
